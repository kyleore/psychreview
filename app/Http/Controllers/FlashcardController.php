<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Topic::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $cards = $query->orderBy('title')->get();

        if ($request->boolean('shuffle')) {
            $cards = $cards->shuffle()->values();
        }

        return view('flashcards.index', [
            'categories' => $categories,
            'cards' => $cards,
            'active' => $request->category,
        ]);
    }
}
