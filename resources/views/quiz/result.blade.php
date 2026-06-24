@extends('layouts.app')
@section('title', 'Quiz Results')

@section('content')
<section class="mx-auto max-w-3xl px-4 py-12">
    @php
        $tone = $percent >= 80 ? ['from-emerald-500','to-teal-600','text-emerald-600','Excellent work!','party-popper']
              : ($percent >= 50 ? ['from-amber-500','to-orange-600','text-amber-600','Good effort — keep practicing!','thumbs-up']
              : ['from-rose-500','to-pink-600','text-rose-600','Keep studying — you can do this!','book-open']);
    @endphp

    <div class="animate-pop rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-xl shadow-brand-500/5">
        <div class="relative mx-auto grid h-36 w-36 place-items-center">
            <svg class="absolute inset-0 h-full w-full -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="44" fill="none" stroke="#eef2ff" stroke-width="10"/>
                <circle cx="50" cy="50" r="44" fill="none" stroke="url(#g)" stroke-width="10" stroke-linecap="round"
                        stroke-dasharray="276.46" stroke-dashoffset="{{ 276.46 * (1 - $percent/100) }}"
                        style="transition:stroke-dashoffset 1s ease"/>
                <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#6366f1"/><stop offset="100%" stop-color="#7c3aed"/>
                </linearGradient></defs>
            </svg>
            <div>
                <div class="text-4xl font-extrabold text-slate-900">{{ $percent }}%</div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">Score</div>
            </div>
        </div>
        <p class="mt-5 inline-flex items-center gap-2 text-xl font-extrabold {{ $tone[2] }}">
            <i data-lucide="{{ $tone[4] }}" class="h-6 w-6"></i> {{ $tone[3] }}
        </p>
        <p class="mt-1 text-slate-500">You answered <span class="font-bold text-slate-700">{{ $score }}</span> out of <span class="font-bold text-slate-700">{{ $total }}</span> correctly.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('quiz.index') }}" class="btn-press inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-600 to-violet-600 px-5 py-3 font-bold text-white shadow-lg shadow-brand-500/30"><i data-lucide="rotate-cw" class="h-5 w-5"></i> Try Again</a>
            <a href="{{ route('progress.index') }}" class="btn-press inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 font-bold text-brand-700 ring-1 ring-slate-200 hover:bg-slate-50"><i data-lucide="bar-chart-3" class="h-5 w-5"></i> View Progress</a>
        </div>
    </div>

    <h2 class="mt-10 flex items-center gap-2 text-lg font-extrabold text-slate-900"><i data-lucide="list-checks" class="h-5 w-5 text-brand-600"></i> Review your answers</h2>
    <div class="stagger mt-4 space-y-4">
        @foreach($results as $r)
            @php $q = $r['question']; @endphp
            <div class="rounded-2xl border bg-white p-5 shadow-sm {{ $r['correct'] ? 'border-emerald-200' : 'border-rose-200' }}">
                <div class="flex items-start gap-3">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full {{ $r['correct'] ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                        <i data-lucide="{{ $r['correct'] ? 'check' : 'x' }}" class="h-4 w-4"></i>
                    </span>
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-900">{{ $q->question }}</h3>
                        @auth
                            @php $lvl = $r['level'] ?? 1; @endphp
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[11px] font-bold
                                    {{ $lvl >= 5 ? 'bg-amber-100 text-amber-700' : ($lvl >= 3 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500') }}">
                                    <i data-lucide="{{ $lvl >= 5 ? 'crown' : 'star' }}" class="h-3 w-3"></i> Level {{ $lvl }}
                                </span>
                                @if(!empty($r['leveled_up']))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-600">
                                        <i data-lucide="arrow-up" class="h-3 w-3"></i> Levelled up!
                                    </span>
                                @elseif(($r['level'] ?? 1) < ($r['old_level'] ?? 1))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-bold text-rose-500">
                                        <i data-lucide="arrow-down" class="h-3 w-3"></i> Levelled down
                                    </span>
                                @endif
                            </div>
                        @endauth
                        <div class="mt-2 space-y-1.5 text-sm">
                            @foreach($q->options as $idx => $opt)
                                @php
                                    $isCorrect = $idx === (int)$q->correct_index;
                                    $isChosen = $idx === (int)$r['choice'];
                                @endphp
                                <div class="flex items-center gap-2 rounded-lg px-3 py-1.5
                                    {{ $isCorrect ? 'bg-emerald-50 font-semibold text-emerald-700' : ($isChosen ? 'bg-rose-50 font-semibold text-rose-700 line-through' : 'text-slate-500') }}">
                                    <span class="font-bold">{{ chr(65+$idx) }}.</span> {{ $opt }}
                                    @if($isCorrect)<i data-lucide="check" class="ml-auto h-4 w-4"></i>
                                    @elseif($isChosen)<i data-lucide="x" class="ml-auto h-4 w-4"></i>@endif
                                </div>
                            @endforeach
                        </div>
                        @if($q->explanation)
                            <p class="mt-2 flex items-start gap-1.5 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                <i data-lucide="info" class="mt-0.5 h-4 w-4 shrink-0 text-brand-500"></i> {{ $q->explanation }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
