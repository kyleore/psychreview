<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LibraryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['topics' => function ($q) {
            $q->orderBy('title');
        }])->orderBy('name')->get();

        return view('library.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Use AI (Gemini + Google Search grounding) to find real, downloadable
     * PDF study resources online for a topic — like a Google "filetype:pdf"
     * search, returned as a clean list of links the user can open/download.
     */
    public function findPdfs(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'integer', 'exists:topics,id'],
        ]);

        $topic = Topic::with('category')->findOrFail($data['topic_id']);
        $googleUrl = 'https://www.google.com/search?q='.urlencode($topic->title.' psychology filetype:pdf');

        // 1) Try Google Programmable Search (reliable, direct PDF links, no AI quota).
        // 2) Fall back to Gemini web-grounded search if CSE isn't configured / returns nothing.
        $resources = $this->searchPdfsWithCse($topic);
        $source = $resources ? 'google_cse' : null;

        if (! $resources) {
            $resources = $this->searchPdfsWithGemini($topic);
            $source = $resources ? 'ai' : 'google';
        }

        return response()->json([
            'topic' => $topic->title,
            'resources' => $resources,
            'google_url' => $googleUrl,
            'source' => $source,
        ]);
    }

    /**
     * Google Programmable Search (Custom Search JSON API) filtered to PDFs.
     * Returns real, directly-downloadable document links. Empty if not configured.
     */
    private function searchPdfsWithCse(Topic $topic): array
    {
        $key = config('services.google_cse.key');
        $cx = config('services.google_cse.cx');
        if (! $key || ! $cx) {
            return [];
        }

        try {
            $response = Http::timeout(20)->get('https://www.googleapis.com/customsearch/v1', [
                'key' => $key,
                'cx' => $cx,
                'q' => $topic->title.' psychology',
                'fileType' => 'pdf',
                'num' => 10,
                'safe' => 'active',
            ]);

            if (! $response->successful()) {
                report(new \RuntimeException('Google CSE error '.$response->status().': '.$response->body()));

                return [];
            }

            $resources = [];
            foreach ($response->json('items', []) as $item) {
                if (! empty($item['link']) && filter_var($item['link'], FILTER_VALIDATE_URL)) {
                    $resources[] = [
                        'title' => trim($item['title'] ?? $item['link']),
                        'url' => $item['link'],
                        'snippet' => trim($item['snippet'] ?? ''),
                    ];
                }
            }

            return $this->dedupeResources($resources);
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * Ask Gemini (with Google Search) for real downloadable PDF documents.
     * Returns a list of ['title' => ..., 'url' => ...]. Empty array on failure.
     */
    private function searchPdfsWithGemini(Topic $topic): array
    {
        $key = config('services.gemini.key');
        if (! $key) {
            return [];
        }

        $system = 'You are a research assistant for psychology students preparing for the PRC '
            .'Psychometrician Licensure Exam. Use Google Search to find REAL, currently available, '
            .'downloadable PDF study materials (open textbooks, lecture notes, reviewers, journal articles) '
            .'about the given topic. Only include links that point to actual PDF files or pages that host '
            .'a downloadable PDF. Prefer reputable sources (universities, open-access textbooks, '
            .'government/professional bodies). Do not invent links.';

        $prompt = "Topic: {$topic->title}\n"
            ."Category: {$topic->category->name}\n\n"
            .'Find 5-8 downloadable PDF resources about this topic. '
            .'Respond ONLY with a JSON array (no markdown fences) where each item is '
            .'{"title": "document title", "url": "https://direct-link-to-pdf"}. '
            .'Use the real URLs you found via Google Search.';

        $model = config('services.gemini.model');
        $base = rtrim(config('services.gemini.base_url'), '/');
        $url = "{$base}/models/{$model}:generateContent";

        $payload = [
            'system_instruction' => ['parts' => [['text' => $system]]],
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'generationConfig' => ['temperature' => 0.2],
            'tools' => [['google_search' => new \stdClass]],
        ];

        try {
            $response = Http::timeout(40)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, $payload);

            if (! $response->successful()) {
                report(new \RuntimeException('Gemini PDF search error '.$response->status().': '.$response->body()));

                return [];
            }

            $text = '';
            foreach ($response->json('candidates.0.content.parts', []) as $part) {
                if (isset($part['text'])) {
                    $text .= $part['text'];
                }
            }

            $resources = $this->parseResources($text);

            // Add grounding sources (real URLs Google returned) as extra links.
            foreach ($response->json('candidates.0.groundingMetadata.groundingChunks', []) as $chunk) {
                if (! empty($chunk['web']['uri'])) {
                    $resources[] = [
                        'title' => $chunk['web']['title'] ?? $chunk['web']['uri'],
                        'url' => $chunk['web']['uri'],
                    ];
                }
            }

            return $this->dedupeResources($resources);
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * Extract a list of {title, url} from the model's JSON-ish response,
     * tolerant of stray markdown fences or surrounding prose.
     */
    private function parseResources(string $text): array
    {
        $text = trim($text);
        if ($text === '') {
            return [];
        }

        // Pull the first JSON array out of the text if it's wrapped in prose.
        if (preg_match('/\[.*\]/s', $text, $m)) {
            $decoded = json_decode($m[0], true);
            if (is_array($decoded)) {
                $out = [];
                foreach ($decoded as $item) {
                    if (! empty($item['url']) && filter_var($item['url'], FILTER_VALIDATE_URL)) {
                        $out[] = [
                            'title' => trim($item['title'] ?? $item['url']),
                            'url' => $item['url'],
                        ];
                    }
                }

                return $out;
            }
        }

        return [];
    }

    private function dedupeResources(array $resources): array
    {
        $seen = [];
        $out = [];
        foreach ($resources as $r) {
            $u = $r['url'] ?? '';
            if ($u === '' || isset($seen[$u])) {
                continue;
            }
            $seen[$u] = true;
            $out[] = $r;
        }

        return array_slice($out, 0, 12);
    }
}
