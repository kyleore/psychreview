@extends('layouts.app')
@section('title', 'Quiz')

@section('content')
@if($mode === 'picker')
<section class="mx-auto max-w-4xl px-4 py-12">
    <div class="animate-fade-up">
        <h1 class="flex items-center gap-3 text-3xl font-extrabold tracking-tight text-slate-900">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="clipboard-check" class="h-6 w-6"></i>
            </span>
            Practice Quiz
        </h1>
        <p class="mt-2 text-slate-500">Choose a topic to start a focused quiz, or take a mixed quiz across all topics.</p>
    </div>

    @if(session('status'))
        <div class="mt-6 flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <i data-lucide="check-circle-2" class="h-4 w-4"></i> {{ session('status') }}
        </div>
    @endif
    @if(session('quiz_error'))
        <div class="mt-6 flex items-center gap-2 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
            <i data-lucide="alert-triangle" class="h-4 w-4"></i> {{ session('quiz_error') }}
        </div>
    @endif

    @auth
        @if(auth()->user()->is_admin)
        <div class="mt-6 rounded-3xl border border-brand-100 bg-brand-50/60 p-5">
            <div class="flex items-center gap-2 text-sm font-bold text-brand-700">
                <i data-lucide="sparkles" class="h-4 w-4"></i> Generate new questions with AI
            </div>
            <p class="mt-1 text-xs text-slate-500">Pick a category and how many questions to add. New questions are saved to the quiz pool for everyone.</p>
            <form method="POST" action="{{ route('quiz.generate') }}" data-no-skeleton x-data="{ loading:false }" @submit="loading=true" class="mt-3 flex flex-wrap items-center gap-2">
                @csrf
                <select name="category" required class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                    <option value="" disabled selected>Choose a category…</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="count" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
                    <option value="3">3 questions</option>
                    <option value="5" selected>5 questions</option>
                    <option value="10">10 questions</option>
                </select>
                <button type="submit" :disabled="loading"
                        class="btn-press inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-2 text-sm font-bold text-white shadow-md shadow-brand-500/30 transition hover:shadow-lg disabled:opacity-60">
                    <i data-lucide="wand-sparkles" class="h-4 w-4" x-show="!loading"></i>
                    <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span x-text="loading ? 'Generating…' : 'Generate'"></span>
                </button>
            </form>
        </div>
        @endif
    @endauth

    <!-- Mixed quiz (all topics) -->
    <a href="{{ route('quiz.index', ['category' => 'all']) }}"
       class="lift mt-8 flex items-center justify-between gap-4 rounded-3xl bg-gradient-to-r from-brand-600 to-violet-600 p-6 text-white shadow-xl shadow-brand-500/30">
        <div class="flex items-center gap-4">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white/20">
                <i data-lucide="shuffle" class="h-6 w-6"></i>
            </span>
            <div>
                <h2 class="text-lg font-bold">Mixed Quiz</h2>
                <p class="text-sm text-white/80">Random questions from every category · {{ $totalQuestions }} available</p>
            </div>
        </div>
        <i data-lucide="arrow-right" class="h-6 w-6"></i>
    </a>

    <p class="mt-8 text-sm font-bold uppercase tracking-wide text-slate-400">Quiz by topic</p>
    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        @foreach($categories as $cat)
            <a href="{{ route('quiz.index', ['category' => $cat->slug]) }}"
               class="lift group flex items-center justify-between gap-4 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-600 ring-1 ring-brand-100">
                        <i data-lucide="{{ $cat->icon }}" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-900">{{ $cat->name }}</h3>
                        <p class="text-xs font-medium text-slate-400">{{ $cat->questions_count }} {{ Str::plural('question', $cat->questions_count) }}</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 transition group-hover:translate-x-1 group-hover:text-brand-500"></i>
            </a>
        @endforeach
    </div>
</section>
@else
<section class="mx-auto max-w-3xl px-4 py-12" x-data="quiz()">
    <div class="animate-fade-up">
        <a href="{{ route('quiz.index') }}" class="mb-4 inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600">
            <i data-lucide="arrow-left" class="h-4 w-4"></i> Choose another topic
        </a>
        <h1 class="flex items-center gap-3 text-3xl font-extrabold tracking-tight text-slate-900">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="{{ $category?->icon ?? 'clipboard-check' }}" class="h-6 w-6"></i>
            </span>
            {{ $category?->name ?? 'Mixed' }} Quiz
        </h1>
        <p class="mt-2 text-slate-500">Answer all {{ $questions->count() }} questions, then check your score.</p>
    </div>

    @if($questions->isEmpty())
        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-6 text-center text-amber-700">
            No questions available for this topic yet.
        </div>
    @else
    <!-- Progress bar -->
    <div class="sticky top-[68px] z-10 mt-6 rounded-2xl border border-slate-100 bg-white/90 p-4 shadow-sm backdrop-blur">
        <div class="flex items-center justify-between text-sm font-semibold text-slate-500">
            <span><span x-text="answered"></span> / {{ $questions->count() }} answered</span>
            <span x-text="Math.round(answered/{{ $questions->count() }}*100)+'%'"></span>
        </div>
        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
            <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-violet-500 transition-all duration-500" :style="`width:${answered/{{ $questions->count() }}*100}%`"></div>
        </div>
    </div>

    <form method="POST" action="{{ route('quiz.submit') }}" @submit="syncCount" class="mt-6 space-y-5">
        @csrf
        @foreach($questions as $i => $question)
            <div class="stagger-none rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-extrabold text-brand-700">{{ $i+1 }}</span>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                            <i data-lucide="{{ $question->topic->category->icon }}" class="h-3.5 w-3.5"></i> {{ $question->topic->category->name }}
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $question->question }}</h3>
                    </div>
                </div>
                <div class="mt-4 grid gap-2.5 sm:pl-11">
                    @foreach(collect($question->options)->map(fn($opt, $i) => ['i' => $i, 'text' => $opt])->shuffle() as $pos => $entry)
                        <label class="group flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 transition hover:border-brand-300 hover:bg-brand-50/50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50 has-[:checked]:ring-1 has-[:checked]:ring-brand-400">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $entry['i'] }}" class="peer sr-only" @change="syncCount">
                            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full border-2 border-slate-300 text-xs font-bold text-slate-400 transition peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-checked:text-white">{{ chr(65+$pos) }}</span>
                            <span class="text-sm font-medium text-slate-700">{{ $entry['text'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn-press sticky bottom-20 md:bottom-4 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-600 to-violet-600 px-6 py-4 text-lg font-bold text-white shadow-xl shadow-brand-500/30 transition hover:shadow-2xl">
            <i data-lucide="check-circle-2" class="h-5 w-5"></i> Submit & See Results
        </button>
    </form>
    @endif
</section>
@endif
@endsection

@push('scripts')
<script>
    function quiz(){
        return {
            answered:0,
            syncCount(){
                this.answered = new Set(
                    [...this.$root.querySelectorAll('input[type=radio]:checked')].map(r=>r.name)
                ).size;
            }
        }
    }
</script>
@endpush
