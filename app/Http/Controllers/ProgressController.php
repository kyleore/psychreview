<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $sessionId = $request->session()->getId();
        $attempts = QuizAttempt::where('session_id', $sessionId)
            ->latest()
            ->get();

        $avg = $attempts->count()
            ? round($attempts->sum(fn ($a) => $a->total ? $a->score / $a->total * 100 : 0) / $attempts->count())
            : 0;

        $best = $attempts->max(fn ($a) => $a->total ? round($a->score / $a->total * 100) : 0) ?? 0;

        $categories = Category::withCount('topics')->orderBy('name')->get();

        return view('progress.index', [
            'attempts' => $attempts,
            'avg' => $avg,
            'best' => $best,
            'topics' => Topic::count(),
            'categories' => $categories,
        ]);
    }
}
