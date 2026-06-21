@extends('layouts.app')

@section('title', 'Admin · ' . $user->name)

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8">
    <a href="{{ route('admin.users') }}" class="mb-4 inline-flex items-center gap-1 text-xs font-semibold text-slate-400 hover:text-brand-600">
        <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> All users
    </a>

    @if (session('admin_error'))
        <div class="mb-5 flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            <i data-lucide="alert-triangle" class="h-4 w-4"></i> {{ session('admin_error') }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-violet-600 text-2xl font-extrabold text-white shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h1>
                        @if ($user->is_admin)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-700">
                                <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Admin
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Joined {{ $user->created_at?->format('M j, Y') }}
                        @if ($user->onboarded_at) · Onboarded @else · <span class="text-amber-600">Not onboarded</span> @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-amber-300 px-3.5 py-1.5 text-sm font-semibold text-amber-700 transition hover:bg-amber-50">
                        <i data-lucide="{{ $user->is_admin ? 'shield-off' : 'shield-plus' }}" class="h-4 w-4"></i>
                        {{ $user->is_admin ? 'Remove admin' : 'Make admin' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-rose-300 px-3.5 py-1.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">
                        <i data-lucide="trash-2" class="h-4 w-4"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-2xl font-extrabold text-slate-900">{{ $attempts->count() }}</p>
                <p class="text-xs font-medium text-slate-500">Quizzes taken</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-2xl font-extrabold text-slate-900">{{ $avgScore }}%</p>
                <p class="text-xs font-medium text-slate-500">Average score</p>
            </div>
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-2xl font-extrabold text-slate-900">{{ $attempts->max('score') ?? 0 }}</p>
                <p class="text-xs font-medium text-slate-500">Best raw score</p>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="font-bold text-slate-800">Quiz history</h2>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($attempts as $attempt)
                @php $pct = $attempt->total ? round($attempt->score / $attempt->total * 100) : 0; @endphp
                <li class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-full {{ $pct >= 75 ? 'bg-emerald-100 text-emerald-700' : ($pct >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }} text-xs font-bold">
                            {{ $pct }}%
                        </span>
                        <span class="text-sm font-semibold text-slate-700">{{ $attempt->score }} / {{ $attempt->total }} correct</span>
                    </div>
                    <span class="text-xs text-slate-400">{{ $attempt->created_at?->format('M j, Y g:i A') }}</span>
                </li>
            @empty
                <li class="px-5 py-8 text-center text-sm text-slate-400">This user hasn't taken any quizzes yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
