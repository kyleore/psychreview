@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="relative overflow-hidden">
    <div class="hero-gradient">
        <div class="mx-auto max-w-6xl px-4 py-20 text-center text-white">
            <span class="inline-flex animate-fade-in items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold ring-1 ring-white/25 backdrop-blur">
                <i data-lucide="graduation-cap" class="h-4 w-4"></i> PRC Psychometrician Board Exam Reviewer
            </span>
            <h1 class="mx-auto mt-6 max-w-3xl animate-fade-up text-4xl font-extrabold leading-tight tracking-tight sm:text-6xl">
                Pass the Psychometrician<br><span class="bg-gradient-to-r from-amber-200 to-white bg-clip-text text-transparent">Licensure Exam</span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl animate-fade-up text-lg text-white/85" style="animation-delay:.1s">
                A complete reviewer for the PRC Psychometrician Licensure Exam (RA 10029) — aligned with the board exam coverage: Theories of Personality, Psychological Assessment, Abnormal, and Industrial/Organizational Psychology. Includes flashcards, practice quizzes, and an AI Tutor that explains every topic clearly in English.
            </p>
            <div class="mt-9 flex animate-fade-up flex-wrap items-center justify-center gap-3" style="animation-delay:.2s">
                <a href="{{ route('flashcards.index') }}" class="btn-press inline-flex items-center gap-2 rounded-xl bg-white px-6 py-3 font-bold text-brand-700 shadow-xl shadow-black/10 transition hover:bg-amber-50">
                    <i data-lucide="layers" class="h-5 w-5"></i> Start Flashcards
                </a>
                <a href="{{ route('quiz.index') }}" class="btn-press inline-flex items-center gap-2 rounded-xl bg-brand-700/40 px-6 py-3 font-bold text-white ring-1 ring-white/40 backdrop-blur transition hover:bg-brand-700/60">
                    <i data-lucide="clipboard-check" class="h-5 w-5"></i> Take a Quiz
                </a>
                <a href="{{ route('ai.index') }}" class="btn-press inline-flex items-center gap-2 rounded-xl bg-amber-400 px-6 py-3 font-bold text-amber-950 shadow-xl shadow-black/10 transition hover:bg-amber-300">
                    <i data-lucide="sparkles" class="h-5 w-5"></i> Ask the AI Tutor
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="mx-auto -mt-10 max-w-5xl px-4">
        <div class="stagger grid grid-cols-2 gap-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-brand-500/5 sm:grid-cols-4">
            @php $cards = [
                ['icon'=>'book-open','label'=>'Topics','value'=>$stats['topics']],
                ['icon'=>'layout-grid','label'=>'Categories','value'=>$stats['categories']],
                ['icon'=>'clipboard-check','label'=>'Quizzes Taken','value'=>$stats['quizzes_taken']],
                ['icon'=>'trophy','label'=>'Avg Score','value'=>$stats['avg_score'].'%'],
            ]; @endphp
            @foreach($cards as $c)
                <div class="flex flex-col items-center rounded-2xl px-3 py-4 text-center transition hover:bg-slate-50">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-600">
                        <i data-lucide="{{ $c['icon'] }}" class="h-6 w-6"></i>
                    </span>
                    <span class="mt-3 text-3xl font-extrabold text-slate-900">{{ $c['value'] }}</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $c['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Browse by category -->
<section class="mx-auto max-w-6xl px-4 py-16">
    <div class="flex items-end justify-between">
        <div>
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Browse by Category</h2>
            <p class="mt-1 text-slate-500">Explore {{ $stats['topics'] }} concepts across {{ $stats['categories'] }} categories.</p>
        </div>
        <a href="{{ route('topics.index') }}" class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-brand-600 hover:bg-brand-50 sm:inline-flex">
            View all <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    </div>

    <div class="stagger mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($categories as $category)
            <a href="{{ route('topics.index', ['category' => $category->slug]) }}"
               class="lift group flex flex-col rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                @include('partials.icon-tile', ['category' => $category, 'size' => 'lg'])
                <h3 class="mt-5 text-lg font-bold text-slate-900 group-hover:text-brand-700">{{ $category->name }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $category->topics_count }} {{ Str::plural('topic', $category->topics_count) }}</p>
                <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 opacity-0 transition group-hover:opacity-100">
                    Study now <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </span>
            </a>
        @endforeach
    </div>
</section>
@endsection
