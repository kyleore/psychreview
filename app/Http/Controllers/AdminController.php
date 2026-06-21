<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $attempts = QuizAttempt::all();

        $stats = [
            'users' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'attempts' => $attempts->count(),
            'topics' => Topic::count(),
            'categories' => Category::count(),
            'questions' => QuizQuestion::count(),
            'avg_score' => $attempts->count()
                ? round($attempts->sum(fn ($a) => $a->total ? $a->score / $a->total * 100 : 0) / $attempts->count())
                : 0,
        ];

        $recentUsers = User::latest()->take(8)->get();

        $recentAttempts = QuizAttempt::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAttempts'));
    }

    public function users(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $users = User::query()
            ->withCount('quizAttempts')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function showUser(User $user)
    {
        $attempts = QuizAttempt::where('user_id', $user->id)->latest()->get();

        $avgScore = $attempts->count()
            ? round($attempts->sum(fn ($a) => $a->total ? $a->score / $a->total * 100 : 0) / $attempts->count())
            : 0;

        return view('admin.user', compact('user', 'attempts', 'avgScore'));
    }

    public function toggleAdmin(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('admin_error', "You can't change your own admin status.");
        }

        $user->forceFill(['is_admin' => ! $user->is_admin])->save();

        $state = $user->is_admin ? 'now an admin' : 'no longer an admin';

        return back()->with('status', "{$user->name} is {$state}.");
    }

    public function destroyUser(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('admin_error', "You can't delete your own account.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users')->with('status', "{$name} has been deleted.");
    }
}
