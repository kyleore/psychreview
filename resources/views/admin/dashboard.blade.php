@extends('layouts.app')

@section('title', 'Admin · Dashboard')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Admin dashboard</h1>
            <p class="text-sm text-slate-500">Monitor users, content and quiz activity.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-brand-500/30 transition hover:shadow-lg">
            <i data-lucide="users" class="h-4 w-4"></i> Manage users
        </a>
    </div>

    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <i data-lucide="check-circle-2" class="h-4 w-4"></i> {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Total users', 'value' => $stats['users'], 'icon' => 'users', 'color' => 'from-brand-500 to-violet-600'],
                ['label' => 'Admins', 'value' => $stats['admins'], 'icon' => 'shield-check', 'color' => 'from-amber-500 to-orange-600'],
                ['label' => 'Quizzes taken', 'value' => $stats['attempts'], 'icon' => 'clipboard-check', 'color' => 'from-emerald-500 to-teal-600'],
                ['label' => 'Avg score', 'value' => $stats['avg_score'].'%', 'icon' => 'trending-up', 'color' => 'from-sky-500 to-blue-600'],
                ['label' => 'Topics', 'value' => $stats['topics'], 'icon' => 'book-open', 'color' => 'from-fuchsia-500 to-pink-600'],
                ['label' => 'Categories', 'value' => $stats['categories'], 'icon' => 'layout-grid', 'color' => 'from-indigo-500 to-violet-600'],
                ['label' => 'Questions', 'value' => $stats['questions'], 'icon' => 'help-circle', 'color' => 'from-rose-500 to-red-600'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <span class="mb-3 grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br {{ $card['color'] }} text-white shadow">
                    <i data-lucide="{{ $card['icon'] }}" class="h-5 w-5"></i>
                </span>
                <p class="text-2xl font-extrabold text-slate-900">{{ $card['value'] }}</p>
                <p class="text-xs font-medium text-slate-500">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-800">Newest users</h2>
                <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-brand-600 hover:underline">View all</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentUsers as $user)
                    <li class="flex items-center justify-between px-5 py-3">
                        <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 group">
                            <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-slate-800 group-hover:text-brand-600">{{ $user->name }}</span>
                                <span class="block text-xs text-slate-400">{{ $user->email }}</span>
                            </span>
                        </a>
                        <span class="text-xs text-slate-400">{{ $user->created_at?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-sm text-slate-400">No users yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-800">Recent quiz attempts</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentAttempts as $attempt)
                    <li class="flex items-center justify-between px-5 py-3">
                        <span class="text-sm font-semibold text-slate-700">
                            {{ $attempt->user?->name ?? 'Guest' }}
                        </span>
                        <span class="flex items-center gap-3">
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-600">{{ $attempt->score }}/{{ $attempt->total }}</span>
                            <span class="text-xs text-slate-400">{{ $attempt->created_at?->diffForHumans() }}</span>
                        </span>
                    </li>
                @empty
                    <li class="px-5 py-6 text-center text-sm text-slate-400">No quiz attempts yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
