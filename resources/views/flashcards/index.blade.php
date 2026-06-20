@extends('layouts.app')
@section('title', 'Flashcards')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-12">
    <div class="flex flex-wrap items-end justify-between gap-4 animate-fade-up">
        <div>
            <h1 class="flex items-center gap-3 text-3xl font-extrabold tracking-tight text-slate-900">
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                    <i data-lucide="layers" class="h-6 w-6"></i>
                </span>
                Flashcards
            </h1>
            <p class="mt-2 text-slate-500">Tap a card to reveal the answer. Flip back anytime.</p>
        </div>
        <a href="{{ route('flashcards.index', array_merge(['shuffle' => 1], $active ? ['category' => $active] : [])) }}"
           class="btn-press inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 font-semibold text-brand-700 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50">
            <i data-lucide="shuffle" class="h-4 w-4"></i> Shuffle
        </a>
    </div>

    <!-- Filters -->
    <div class="mt-7 flex flex-wrap gap-2">
        <a href="{{ route('flashcards.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold transition btn-press {{ !$active ? 'bg-brand-600 text-white shadow-md shadow-brand-500/30' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">All</a>
        @foreach($categories as $category)
            <a href="{{ route('flashcards.index', ['category' => $category->slug]) }}"
               class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-sm font-semibold transition btn-press {{ $active === $category->slug ? 'bg-brand-600 text-white shadow-md shadow-brand-500/30' : 'bg-white text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50' }}">
                <i data-lucide="{{ $category->icon }}" class="h-4 w-4"></i> {{ $category->name }}
            </a>
        @endforeach
    </div>

    <div class="stagger mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($cards as $card)
            <div class="flip h-72" x-data="{flipped:false}" :class="flipped && 'is-flipped'" @click="flipped=!flipped" role="button" tabindex="0" @keydown.enter="flipped=!flipped">
                <div class="flip-inner cursor-pointer">
                    <!-- Front -->
                    <div class="flip-face border border-slate-100 bg-white p-6 shadow-lg shadow-brand-500/5">
                        <div class="flex items-center justify-between">
                            @include('partials.icon-tile', ['category' => $card->category, 'size' => 'sm'])
                            @include('partials.difficulty', ['difficulty' => $card->difficulty])
                        </div>
                        <div class="flex flex-1 flex-col items-center justify-center text-center">
                            <p class="text-xs font-bold uppercase tracking-widest text-brand-500">{{ $card->category->name }}</p>
                            <h3 class="mt-2 text-xl font-extrabold text-slate-900">{{ $card->title }}</h3>
                        </div>
                        <p class="flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-400">
                            <i data-lucide="mouse-pointer-click" class="h-3.5 w-3.5"></i> Tap to reveal answer
                        </p>
                    </div>
                    <!-- Back -->
                    <div class="flip-face flip-back border border-brand-200 bg-gradient-to-br from-brand-600 to-violet-700 p-6 text-white shadow-xl shadow-brand-500/30">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-white/70">Definition</p>
                        <div class="mt-1 flex-1 overflow-auto pr-1">
                            <p class="text-sm leading-relaxed text-white/95">{{ $card->definition }}</p>
                            @if($card->example)
                                <p class="mt-3 text-[11px] font-bold uppercase tracking-widest text-amber-200">Example</p>
                                <p class="text-sm leading-relaxed text-white/90">{{ $card->example }}</p>
                            @endif
                        </div>
                        <p class="flex items-center justify-center gap-1.5 text-xs font-semibold text-white/70">
                            <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i> Tap to flip back
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
