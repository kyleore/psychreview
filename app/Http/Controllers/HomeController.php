<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('topics')->orderBy('name')->get();

        $sessionId = $request->session()->getId();
        $attempts = QuizAttempt::where('session_id', $sessionId)->get();

        $stats = [
            'topics' => Topic::count(),
            'categories' => Category::count(),
            'quizzes_taken' => $attempts->count(),
            'avg_score' => $attempts->count()
                ? round($attempts->sum(fn ($a) => $a->total ? $a->score / $a->total * 100 : 0) / $attempts->count())
                : 0,
        ];

        return view('home', compact('categories', 'stats'));
    }
}
