@extends('layouts.app')
@section('title', 'Welcome — Quick Tour')

@section('content')
<section class="relative overflow-hidden">
    <div class="hero-gradient">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center text-white">
            <span class="inline-flex animate-fade-in items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-sm font-semibold ring-1 ring-white/25 backdrop-blur">
                <i data-lucide="hand-heart" class="h-4 w-4"></i> Welcome, {{ Auth::user()->name }}!
            </span>
            <h1 class="mx-auto mt-6 max-w-2xl animate-fade-up text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                Here's everything PsychReview<br>can do for you
            </h1>
            <p class="mx-auto mt-5 max-w-2xl animate-fade-up text-lg text-white/85" style="animation-delay:.1s">
                A complete reviewer for the PRC Psychometrician Licensure Exam (RA 10029). Take a 1-minute tour of each feature below, then jump in.
            </p>
        </div>
    </div>
</section>

<section class="mx-auto max-w-5xl px-4 py-14">
    @php
        $features = [
            [
                'icon' => 'book-open',
                'color' => 'from-sky-500 to-blue-600',
                'title' => 'Topics Library',
                'desc' => 'Browse every board-exam concept organized into categories like Theories of Personality, Psychological Assessment, Abnormal, and Industrial/Organizational Psychology. Each topic has a clear definition, key points, and a real-world example.',
                'how' => 'Open Topics → pick a category → tap any concept to read the full breakdown.',
            ],
            [
                'icon' => 'layers',
                'color' => 'from-violet-500 to-purple-600',
                'title' => 'Flashcards',
                'desc' => 'Flip-style cards that show a term on the front and the definition on the back. Perfect for fast memorization and active recall before exam day.',
                'how' => 'Open Flashcards → tap a card to flip → swipe through the deck.',
            ],
            [
                'icon' => 'clipboard-check',
                'color' => 'from-emerald-500 to-green-600',
                'title' => 'Practice Quizzes',
                'desc' => 'Multiple-choice quizzes that mimic the real licensure exam. You can quiz a single category or mix everything. After each quiz you get your score and the correct answers with explanations.',
                'how' => 'Open Quiz → choose a category (or "All") → answer 10 questions → review your results.',
            ],
            [
                'icon' => 'bar-chart-3',
                'color' => 'from-amber-500 to-orange-600',
                'title' => 'Progress Tracker',
                'desc' => 'Every quiz you take is saved to your account. See your average score, your best run, and your full attempt history so you know exactly where to focus.',
                'how' => 'Open Progress any time to view your stats — they follow you on every device you log in from.',
            ],
            [
                'icon' => 'library-big',
                'color' => 'from-rose-500 to-pink-600',
                'title' => 'PDF Library',
                'desc' => 'Search for free reviewer PDFs and reference materials on any psychology topic, gathered from the web so you can read deeper beyond the built-in notes.',
                'how' => 'Open Library → type a topic → open the suggested PDFs.',
            ],
            [
                'icon' => 'sparkles',
                'color' => 'from-brand-600 to-violet-600',
                'title' => 'AI Tutor',
                'desc' => 'Stuck on a concept? Ask the AI Tutor in plain English and get a clear, exam-focused explanation instantly — like having a reviewer on call 24/7.',
                'how' => 'Open AI Tutor → type your question → get an instant explanation.',
            ],
        ];
    @endphp

    <div class="stagger grid gap-5 sm:grid-cols-2">
        @foreach($features as $i => $f)
            <div class="lift flex flex-col rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-gradient-to-br {{ $f['color'] }} text-white shadow-lg">
                        <i data-lucide="{{ $f['icon'] }}" class="h-6 w-6"></i>
                    </span>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wide text-slate-400">Step {{ $i + 1 }}</span>
                        <h3 class="text-lg font-extrabold tracking-tight text-slate-900">{{ $f['title'] }}</h3>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-slate-600">{{ $f['desc'] }}</p>
                <div class="mt-4 flex items-start gap-2 rounded-xl bg-slate-50 px-3 py-2.5 text-sm text-slate-500">
                    <i data-lucide="mouse-pointer-click" class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"></i>
                    <span><span class="font-semibold text-slate-700">How:</span> {{ $f['how'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 rounded-3xl border border-brand-100 bg-gradient-to-br from-brand-50 to-violet-50 p-8 text-center">
        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Ready to start reviewing?</h2>
        <p class="mx-auto mt-2 max-w-xl text-slate-600">You can revisit this tour any time from the menu. Let's get you that passing score.</p>
        <form method="POST" action="{{ route('intro.done') }}" class="mt-6">
            @csrf
            <button type="submit"
                class="btn-press inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-violet-600 px-8 py-3.5 font-bold text-white shadow-xl shadow-brand-500/30 transition hover:shadow-2xl">
                <i data-lucide="rocket" class="h-5 w-5"></i> Start exploring
            </button>
        </form>
    </div>
</section>
@endsection
