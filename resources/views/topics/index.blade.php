@extends('layouts.app')
@section('title', 'Topics')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="animate-fade-up">
        <h1 class="flex items-center gap-3 text-3xl font-extrabold tracking-tight text-slate-900">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="book-open" class="h-6 w-6"></i>
            </span>
            Psychology Topics
        </h1>
        <p class="mt-2 text-slate-500">Browse and study key concepts across all categories.</p>
    </div>

    <!-- Filters -->
    <div class="mt-8 flex flex-wrap gap-2">
        <a href="{{ route('topics.index') }}"
           class="rounded-full px-4 py-2 text-sm font-semibold transition btn-press {{ !$active ? 'bg-brand-600 text-white shadow-md shadow-brand-500/30' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
            All
        </a>
        @foreach($categories as $category)
            <a href="{{ route('topics.index', ['category' => $category->slug]) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold transition btn-press {{ $active === $category->slug ? 'bg-brand-600 text-white shadow-md shadow-brand-500/30' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                <i data-lucide="{{ $category->icon }}" class="h-4 w-4"></i> {{ $category->name }}
            </a>
        @endforeach
    </div>

    <p class="mt-6 text-sm font-medium text-slate-400">{{ $topics->count() }} {{ Str::plural('topic', $topics->count()) }} found</p>

    <div class="stagger mt-4 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @foreach($topics as $topic)
            <a href="{{ route('topics.show', $topic) }}" class="lift group flex flex-col rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    @include('partials.icon-tile', ['category' => $topic->category, 'size' => 'sm'])
                    @include('partials.difficulty', ['difficulty' => $topic->difficulty])
                </div>
                <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-brand-700">{{ $topic->title }}</h3>
                <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-500">{{ $topic->definition }}</p>
                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                    <span class="text-xs font-semibold text-slate-400">{{ $topic->category->name }}</span>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-brand-600">
                        Study <i data-lucide="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1"></i>
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    @if($topics->isEmpty())
        <div class="mt-10 rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center">
            <i data-lucide="search-x" class="mx-auto h-10 w-10 text-slate-300"></i>
            <p class="mt-3 font-semibold text-slate-500">No topics found in this category.</p>
        </div>
    @endif
</section>
@endsection
