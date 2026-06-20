@extends('layouts.app')
@section('title', 'Progress')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-12">
    <div class="animate-fade-up">
        <h1 class="flex items-center gap-3 text-3xl font-extrabold tracking-tight text-slate-900">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="bar-chart-3" class="h-6 w-6"></i>
            </span>
            Your Progress
        </h1>
        <p class="mt-2 text-slate-500">Track your quiz performance over time.</p>
    </div>

    <div class="stagger mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php $tiles = [
            ['icon'=>'clipboard-list','label'=>'Quizzes Taken','value'=>$attempts->count(),'tone'=>'from-brand-500 to-violet-600'],
            ['icon'=>'percent','label'=>'Average Score','value'=>$avg.'%','tone'=>'from-sky-500 to-blue-600'],
            ['icon'=>'trophy','label'=>'Best Score','value'=>$best.'%','tone'=>'from-amber-500 to-orange-600'],
            ['icon'=>'book-open','label'=>'Topics Available','value'=>$topics,'tone'=>'from-emerald-500 to-teal-600'],
        ]; @endphp
        @foreach($tiles as $t)
            <div class="lift rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $t['tone'] }} text-white shadow-lg">
                    <i data-lucide="{{ $t['icon'] }}" class="h-6 w-6"></i>
                </span>
                <p class="mt-4 text-3xl font-extrabold text-slate-900">{{ $t['value'] }}</p>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $t['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-5">
        <!-- History -->
        <div class="lg:col-span-3">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="flex items-center gap-2 font-extrabold text-slate-900"><i data-lucide="history" class="h-5 w-5 text-brand-600"></i> Recent Attempts</h2>
                @if($attempts->isEmpty())
                    <div class="mt-6 rounded-2xl border border-dashed border-slate-200 p-10 text-center">
                        <i data-lucide="clipboard-x" class="mx-auto h-10 w-10 text-slate-300"></i>
                        <p class="mt-3 font-semibold text-slate-500">No quizzes yet.</p>
                        <a href="{{ route('quiz.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-bold text-white btn-press"><i data-lucide="play" class="h-4 w-4"></i> Take your first quiz</a>
                    </div>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach($attempts as $a)
                            @php $p = $a->total ? round($a->score/$a->total*100) : 0; @endphp
                            <div class="flex items-center gap-4 rounded-2xl border border-slate-100 p-4">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl text-sm font-extrabold {{ $p>=80?'bg-emerald-100 text-emerald-700':($p>=50?'bg-amber-100 text-amber-700':'bg-rose-100 text-rose-700') }}">{{ $p }}%</span>
                                <div class="flex-1">
                                    <p class="font-bold text-slate-800">{{ $a->score }} / {{ $a->total }} correct</p>
                                    <p class="text-xs text-slate-400">{{ $a->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="hidden h-2 w-28 overflow-hidden rounded-full bg-slate-100 sm:block">
                                    <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-violet-500" style="width: {{ $p }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Categories overview -->
        <div class="lg:col-span-2">
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="flex items-center gap-2 font-extrabold text-slate-900"><i data-lucide="layout-grid" class="h-5 w-5 text-violet-600"></i> Categories</h2>
                <div class="mt-4 space-y-2.5">
                    @foreach($categories as $c)
                        <a href="{{ route('topics.index', ['category'=>$c->slug]) }}" class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-slate-50">
                            @include('partials.icon-tile', ['category' => $c, 'size' => 'sm'])
                            <span class="flex-1 text-sm font-semibold text-slate-700">{{ $c->name }}</span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-500">{{ $c->topics_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
