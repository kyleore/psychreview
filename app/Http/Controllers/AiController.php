<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function index()
    {
        $topics = Topic::with('category')->orderBy('title')->get();

        return view('ai.index', compact('topics'));
    }

    public function explain(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['nullable', 'integer', 'exists:topics,id'],
            'question' => ['nullable', 'string', 'max:1000'],
            'mode' => ['nullable', 'in:explain,simplify,example,quiz,reviewer'],
        ]);

        $topic = ! empty($data['topic_id']) ? Topic::with('category')->find($data['topic_id']) : null;
        $mode = $data['mode'] ?? 'explain';
        $question = trim($data['question'] ?? '');

        if (! $topic && $question === '') {
            return response()->json([
                'answer' => 'Please pick a topic or ask a question so I can explain it for you.',
            ], 422);
        }

        $provider = config('services.ai_provider', 'auto');
        $geminiKey = config('services.gemini.key');
        $groqKey = config('services.groq.key');
        $openaiKey = config('services.openai.key');

        // Build an ordered failover chain. The preferred provider goes first,
        // then we automatically fall back to the others if one runs out of
        // tokens/quota (or otherwise fails) — so the tutor keeps working.
        $order = match ($provider) {
            'groq' => ['groq', 'gemini', 'openai'],
            'openai' => ['openai', 'gemini', 'groq'],
            'gemini' => ['gemini', 'groq', 'openai'],
            default => ['gemini', 'groq', 'openai'],
        };

        foreach ($order as $p) {
            if ($p === 'gemini' && $geminiKey) {
                $result = $this->askGemini($geminiKey, $topic, $question, $mode);
                if ($result !== null) {
                    return response()->json($result + ['source' => 'ai', 'provider' => 'gemini']);
                }
            }

            if ($p === 'groq' && $groqKey) {
                $answer = $this->askOpenAiCompatible(
                    $groqKey,
                    config('services.groq.base_url'),
                    config('services.groq.model'),
                    $topic,
                    $question,
                    $mode
                );
                if ($answer !== null) {
                    return response()->json(['answer' => $answer, 'source' => 'ai', 'provider' => 'groq']);
                }
            }

            if ($p === 'openai' && $openaiKey) {
                $answer = $this->askOpenAi($openaiKey, $topic, $question, $mode);
                if ($answer !== null) {
                    return response()->json(['answer' => $answer, 'source' => 'ai', 'provider' => 'openai']);
                }
            }
        }

        return response()->json([
            'answer' => $this->localExplanation($topic, $question, $mode),
            'source' => 'offline',
        ]);
    }

    /**
     * Google Gemini with Google Search grounding so answers are pulled from
     * real online sources (with citations). Returns ['answer' => ..., 'sources' => [...]].
     */
    private function askGemini(string $key, ?Topic $topic, string $question, string $mode): ?array
    {
        $context = '';
        if ($topic) {
            $context = "Reviewer topic: {$topic->title}\n"
                ."Category: {$topic->category->name}\n"
                ."Our definition: {$topic->definition}\n"
                ."Our key points: {$topic->key_points}\n"
                ."Our example: {$topic->example}\n";
        }

        $instruction = match ($mode) {
            'simplify' => 'Explain it in the simplest possible terms, as if to a beginner student.',
            'example' => 'Give two clear, memorable real-life examples.',
            'quiz' => 'Create 3 short practice questions with answers about this topic.',
            'reviewer' => 'Build a comprehensive, board-exam-focused study reviewer about this topic using accurate, '
                .'up-to-date online sources. Structure it with clear headings and bullet points covering: '
                .'(1) Definition / overview, (2) Key concepts and key points, (3) Important theorists or models, '
                .'(4) Common board-exam points and tips, and (5) three practice questions with answers. '
                .'Make it detailed enough to study from on its own.',
            default => 'Explain the concept clearly so a student can master it for the board exam.',
        };

        $system = 'You are PsychTutor, a friendly psychology tutor for students reviewing for the '
            .'PRC Psychometrician Licensure Exam (RA 10029) in the Philippines. '
            .'ONLY answer questions about psychology. If a question is NOT about psychology or the board exam, '
            .'politely decline and redirect the student back to psychology topics. '
            .'Use Google Search to pull answers from accurate, up-to-date online sources '
            .'(prioritize reputable and board-exam-relevant sources). '
            .'Always respond in clear, simple English. Use short paragraphs and bullet points. '
            .'Keep it accurate and exam-focused.';

        $userPrompt = trim($context."\n".$instruction.($question !== '' ? "\n\nStudent's question: ".$question : ''));

        $model = config('services.gemini.model');
        $base = rtrim(config('services.gemini.base_url'), '/');
        $url = "{$base}/models/{$model}:generateContent";

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $system]],
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $userPrompt]]],
            ],
            'generationConfig' => [
                'temperature' => 0.6,
            ],
        ];

        if (config('services.gemini.grounding')) {
            $payload['tools'] = [['google_search' => new \stdClass]];
        }

        try {
            $response = Http::timeout(40)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, $payload);

            if (! $response->successful()) {
                report(new \RuntimeException('Gemini error '.$response->status().': '.$response->body()));

                return null;
            }

            $parts = $response->json('candidates.0.content.parts', []);
            $answer = '';
            foreach ($parts as $part) {
                if (isset($part['text'])) {
                    $answer .= $part['text'];
                }
            }
            $answer = trim($answer);

            if ($answer === '') {
                return null;
            }

            $sources = [];
            foreach ($response->json('candidates.0.groundingMetadata.groundingChunks', []) as $chunk) {
                if (! empty($chunk['web']['uri'])) {
                    $sources[] = [
                        'title' => $chunk['web']['title'] ?? $chunk['web']['uri'],
                        'uri' => $chunk['web']['uri'],
                    ];
                }
            }

            return ['answer' => $answer, 'sources' => $sources];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    private function askOpenAi(string $key, ?Topic $topic, string $question, string $mode): ?string
    {
        return $this->askOpenAiCompatible(
            $key,
            config('services.openai.base_url'),
            config('services.openai.model'),
            $topic,
            $question,
            $mode
        );
    }

    /**
     * Shared OpenAI-compatible chat completion call. Works for OpenAI, Groq,
     * and any other provider exposing a /chat/completions endpoint.
     */
    private function askOpenAiCompatible(string $key, string $baseUrl, string $model, ?Topic $topic, string $question, string $mode): ?string
    {
        $context = '';
        if ($topic) {
            $context = "Topic: {$topic->title}\n"
                ."Category: {$topic->category->name}\n"
                ."Definition: {$topic->definition}\n"
                ."Key points: {$topic->key_points}\n"
                ."Example: {$topic->example}\n";
        }

        $instruction = match ($mode) {
            'simplify' => 'Explain it in the simplest possible terms, as if to a beginner student.',
            'example' => 'Give two clear, memorable real-life examples.',
            'quiz' => 'Create 3 short practice questions with answers about this topic.',
            'reviewer' => 'Build a comprehensive, board-exam-focused study reviewer about this topic. '
                .'Structure it with clear headings and bullet points covering: (1) Definition / overview, '
                .'(2) Key concepts and key points, (3) Important theorists or models, '
                .'(4) Common board-exam points and tips, and (5) three practice questions with answers. '
                .'Make it detailed enough to study from on its own.',
            default => 'Explain the concept clearly so a student can master it for an exam.',
        };

        $system = 'You are PsychTutor, a friendly psychology tutor for students reviewing for the '
            .'PRC Psychometrician Licensure Exam (RA 10029) in the Philippines. ONLY answer questions about psychology; '
            .'politely decline and redirect anything not about psychology or the board exam. '
            .'Explain clearly and warmly in simple English. '
            .'Use short paragraphs and bullet points. Keep it accurate and exam-focused.';

        $userPrompt = trim($context."\n".$instruction.($question !== '' ? "\n\nStudent's question: ".$question : ''));

        try {
            $response = Http::withToken($key)
                ->timeout(30)
                ->post(rtrim($baseUrl, '/').'/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.6,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            report(new \RuntimeException('AI provider error '.$response->status().': '.$response->body()));
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    private function localExplanation(?Topic $topic, string $question, string $mode): string
    {
        if (! $topic) {
            return "Good question! I'm your psychology tutor for the PRC Psychometrician board exam. "
                ."For the best explanation, please pick a topic on the left so I have context — "
                ."or ask a specific psychology question and I'll do my best to help.";
        }

        $lines = [];
        $lines[] = "## {$topic->title}";
        $lines[] = "_{$topic->category->name} · {$topic->difficulty}_";
        $lines[] = '';
        $lines[] = "**What is it?**";
        $lines[] = $topic->definition;
        $lines[] = '';

        if ($mode !== 'example') {
            $lines[] = "**Key points:**";
            foreach (preg_split('/(?<=[.!?])\s+/', (string) $topic->key_points) as $point) {
                $point = trim($point);
                if ($point !== '') {
                    $lines[] = "- {$point}";
                }
            }
            $lines[] = '';
        }

        $lines[] = "**Example:**";
        $lines[] = $topic->example;
        $lines[] = '';

        if ($mode === 'quiz') {
            $q = $topic->questions->first();
            if ($q) {
                $lines[] = "**Practice question:**";
                $lines[] = $q->question;
                foreach ($q->options as $i => $opt) {
                    $lines[] = '- '.chr(65 + $i).". {$opt}";
                }
                $lines[] = '';
                $lines[] = "_Answer: ".chr(65 + $q->correct_index).". {$q->options[$q->correct_index]}_";
            }
        } else {
            $lines[] = "**In simple words:**";
            $lines[] = "Think of it this way: ".$topic->example." This shows how \"{$topic->title}\" works in real life. "
                ."Once you understand the example, it becomes much easier to remember the concept for your exam.";
        }

        if ($question !== '') {
            $lines[] = '';
            $lines[] = "**About your question:** \"{$question}\"";
            $lines[] = "Based on this topic, check the definition and example above — that's where the answer is.";
        }

        return implode("\n", $lines);
    }
}
