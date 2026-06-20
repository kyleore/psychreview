<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $questions = $query->inRandomOrder()->take(10)->get();

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
}
