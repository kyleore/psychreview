<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Topic::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $topics = $query->orderBy('title')->get();

        return view('topics.index', [
            'categories' => $categories,
            'topics' => $topics,
            'active' => $request->category,
        ]);
    }

    public function show(Topic $topic)
    {
        $topic->load('category');

        return view('topics.show', compact('topic'));
    }
}
