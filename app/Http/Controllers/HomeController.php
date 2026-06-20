<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Guests must sign in first.
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Send brand-new users through the feature walkthrough first.
        if (is_null($request->user()->onboarded_at)) {
            return redirect()->route('intro');
        }

        $categories = Category::withCount('topics')->orderBy('name')->get();

        $attempts = QuizAttempt::where('user_id', Auth::id())->get();

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

    public function intro()
    {
        return view('intro');
    }

    public function finishIntro(Request $request)
    {
        $user = $request->user();

        if (is_null($user->onboarded_at)) {
            $user->forceFill(['onboarded_at' => now()])->save();
        }

        return redirect()->route('home')->with('status', "You're all set — happy reviewing!");
    }
}
