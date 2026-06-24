<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $selected = $request->query('category');

        // No category chosen yet -> show the topic/category picker.
        if (! $selected) {
            $categories = Category::withCount(['topics as questions_count' => function ($query) {
                $query->join('quiz_questions', 'quiz_questions.topic_id', '=', 'topics.id');
            }])->orderBy('name')->get();

            $totalQuestions = QuizQuestion::count();

            return view('quiz.index', [
                'mode' => 'picker',
                'categories' => $categories,
                'totalQuestions' => $totalQuestions,
            ]);
        }

        // Build the quiz, optionally scoped to a single category.
        $query = QuizQuestion::with('topic.category');
        $category = null;

        if ($selected !== 'all') {
            $category = Category::where('slug', $selected)->firstOrFail();
            $query->whereHas('topic', fn ($q) => $q->where('category_id', $category->id));
        }

        // A single category shows ALL its questions; the mixed quiz pulls a
        // random sample so it stays a reasonable length.
        $query->inRandomOrder();
        if (! $category) {
            $query->take(15);
        }

        $questions = $query->get();

        return view('quiz.index', [
            'mode' => 'quiz',
            'questions' => $questions,
            'category' => $category,
        ]);
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['nullable', 'integer'],
        ]);

        $questions = QuizQuestion::with('topic')
            ->whereIn('id', array_keys($data['answers']))
            ->get()
            ->keyBy('id');

        $results = [];
        $score = 0;

        foreach ($data['answers'] as $questionId => $choice) {
            $question = $questions->get($questionId);
            if (! $question) {
                continue;
            }

            $isCorrect = (int) $choice === (int) $question->correct_index;
            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $question,
                'choice' => $choice,
                'correct' => $isCorrect,
            ];
        }

        $total = count($results);

        QuizAttempt::create([
            'user_id' => Auth::id(),
            'session_id' => $request->session()->getId(),
            'score' => $score,
            'total' => $total,
        ]);

        return view('quiz.result', [
            'results' => $results,
            'score' => $score,
            'total' => $total,
            'percent' => $total ? round($score / $total * 100) : 0,
        ]);
    }

    /**
     * Admin-only: generate fresh quiz questions for a category with AI and
     * save them into the question pool so everyone can practice with them.
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'exists:categories,slug'],
            'count' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $category = Category::where('slug', $data['category'])->firstOrFail();
        $count = $data['count'] ?? 5;

        $topics = $category->topics()->get();
        if ($topics->isEmpty()) {
            return back()->with('quiz_error', "This category has no topics to attach questions to yet.");
        }

        // Everything we already have for this category (lowercased) so we can
        // detect repeats and tell the AI what NOT to reuse.
        $existing = QuizQuestion::whereIn('topic_id', $topics->pluck('id'))
            ->pluck('question')
            ->map(fn ($q) => mb_strtolower(trim($q)))
            ->flip();

        $added = 0;
        $reachedAi = false;

        // Keep asking the AI until we've saved `count` brand-new questions.
        // If a generated question repeats one we already have, we simply
        // regenerate more until the batch is filled (capped attempts so we
        // never loop forever or burn through API quota).
        for ($attempt = 0; $attempt < 4 && $added < $count; $attempt++) {
            $needed = $count - $added;

            $avoid = $existing->keys()->take(40)->all();
            $generated = $this->generateQuestionsWithAi($category->name, $needed, $avoid);

            if (! empty($generated)) {
                $reachedAi = true;
            }

            foreach ($generated as $item) {
                if ($added >= $count) {
                    break;
                }

                $key = mb_strtolower(trim($item['question']));
                if ($existing->has($key)) {
                    continue; // repeated question -> skip, loop will regenerate
                }

                QuizQuestion::create([
                    'topic_id' => $topics->random()->id,
                    'question' => $item['question'],
                    'options' => $item['options'],
                    'correct_index' => $item['correct_index'],
                    'explanation' => $item['explanation'],
                ]);

                $existing->put($key, true); // remember it for the next round
                $added++;
            }
        }

        if (! $reachedAi) {
            return back()->with('quiz_error', "The AI couldn't generate questions right now (it may be busy). Please try again in a moment.");
        }

        if ($added === 0) {
            return back()->with('quiz_error', 'No new questions could be added — the AI kept repeating ones we already have. Try again.');
        }

        $note = $added < $count
            ? "Added {$added} new AI ".Str::plural('question', $added)." to {$category->name} (some repeats were skipped — try again for more)."
            : "Added {$added} new AI ".Str::plural('question', $added)." to {$category->name}.";

        return back()->with('status', $note);
    }

    /**
     * Ask the AI for board-exam multiple-choice questions and return a
     * validated array of [question, options[], correct_index, explanation].
     * Tries Gemini first, then Groq. Returns [] if both fail.
     *
     * @param  array<int, string>  $avoid  Existing question texts the AI must not repeat.
     */
    private function generateQuestionsWithAi(string $subject, int $count, array $avoid = []): array
    {
        $prompt = "Generate {$count} multiple-choice review questions about \"{$subject}\" "
            .'for the PRC Psychometrician Licensure Exam (RA 10029) in the Philippines. '
            .'Each question must have exactly 4 options and one clearly correct answer. '
            .'Make them accurate, exam-relevant and varied in difficulty. '
            .'Return ONLY a valid JSON array (no markdown, no commentary) where each element is an object: '
            .'{"question": string, "options": [string, string, string, string], '
            .'"correct_index": integer 0-3, "explanation": short string why the answer is correct}.';

        if (! empty($avoid)) {
            $list = collect($avoid)->take(40)->map(fn ($q) => '- '.$q)->implode("\n");
            $prompt .= "\n\nIMPORTANT: Do NOT repeat or paraphrase any of these existing questions. "
                ."Create completely different ones:\n".$list;
        }

        $raw = $this->askGeminiJson($prompt) ?? $this->askGroqJson($prompt);

        if (! $raw) {
            return [];
        }

        return $this->parseQuestions($raw, $count);
    }

    private function askGeminiJson(string $prompt): ?string
    {
        $key = config('services.gemini.key');
        if (! $key) {
            return null;
        }

        $model = config('services.gemini.model');
        $base = rtrim((string) config('services.gemini.base_url'), '/');
        $url = "{$base}/models/{$model}:generateContent";

        try {
            $response = Http::timeout(45)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, [
                    'contents' => [
                        ['role' => 'user', 'parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.8,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

            if (! $response->successful()) {
                report(new \RuntimeException('Gemini quiz error '.$response->status().': '.$response->body()));

                return null;
            }

            $parts = $response->json('candidates.0.content.parts', []);
            $text = '';
            foreach ($parts as $part) {
                $text .= $part['text'] ?? '';
            }

            return trim($text) !== '' ? $text : null;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    private function askGroqJson(string $prompt): ?string
    {
        $key = config('services.groq.key');
        if (! $key) {
            return null;
        }

        try {
            $response = Http::withToken($key)
                ->timeout(40)
                ->post(rtrim((string) config('services.groq.base_url'), '/').'/chat/completions', [
                    'model' => config('services.groq.model'),
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a psychometrician board-exam item writer. Output only valid JSON.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.8,
                    'response_format' => ['type' => 'json_object'],
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            report(new \RuntimeException('Groq quiz error '.$response->status().': '.$response->body()));
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    /**
     * Pull the JSON array of questions out of the raw AI text and validate
     * each item, discarding anything malformed.
     */
    private function parseQuestions(string $raw, int $count): array
    {
        $decoded = json_decode($raw, true);

        // Some models wrap the array in an object, e.g. {"questions": [...]}.
        if (is_array($decoded) && ! array_is_list($decoded)) {
            foreach ($decoded as $value) {
                if (is_array($value) && array_is_list($value)) {
                    $decoded = $value;
                    break;
                }
            }
        }

        // Fallback: grab the first [...] block from the text.
        if (! is_array($decoded) || ! array_is_list($decoded)) {
            if (preg_match('/\[.*\]/s', $raw, $m)) {
                $decoded = json_decode($m[0], true);
            }
        }

        if (! is_array($decoded)) {
            return [];
        }

        $questions = [];
        foreach ($decoded as $item) {
            if (! is_array($item)) {
                continue;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $options = $item['options'] ?? null;
            $index = $item['correct_index'] ?? null;
            $explanation = trim((string) ($item['explanation'] ?? ''));

            if ($question === '' || ! is_array($options)) {
                continue;
            }

            $options = array_values(array_filter(
                array_map(fn ($o) => trim((string) $o), $options),
                fn ($o) => $o !== ''
            ));

            if (count($options) < 2 || ! is_numeric($index)) {
                continue;
            }

            $index = (int) $index;
            if ($index < 0 || $index >= count($options)) {
                continue;
            }

            $questions[] = [
                'question' => $question,
                'options' => $options,
                'correct_index' => $index,
                'explanation' => $explanation !== '' ? $explanation : 'Refer to your reviewer for the detailed explanation.',
            ];

            if (count($questions) >= $count) {
                break;
            }
        }

        return $questions;
    }
}
