@extends('layouts.app')

@section('title', 'Admin · Users')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="mb-1 inline-flex items-center gap-1 text-xs font-semibold text-slate-400 hover:text-brand-600">
                <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i> Dashboard
            </a>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Users</h1>
            <p class="text-sm text-slate-500">{{ $users->total() }} registered {{ Str::plural('account', $users->total()) }}.</p>
        </div>
        <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
            <div class="relative">
                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search name or email"
                       class="w-56 rounded-full border border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200">
            </div>
            <button type="submit" class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Search</button>
        </form>
    </div>

    @if (session('status'))
        <div class="mb-5 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            <i data-lucide="check-circle-2" class="h-4 w-4"></i> {{ session('status') }}
        </div>
    @endif
    @if (session('admin_error'))
        <div class="mb-5 flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            <i data-lucide="alert-triangle" class="h-4 w-4"></i> {{ session('admin_error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">User</th>
                        <th class="px-5 py-3 font-semibold">Quizzes</th>
                        <th class="px-5 py-3 font-semibold">Joined</th>
                        <th class="px-5 py-3 font-semibold">Role</th>
                        <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.users.show', $user) }}" class="flex items-center gap-3 group">
                                    <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-100 text-sm font-bold text-brand-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span>
                                        <span class="block font-semibold text-slate-800 group-hover:text-brand-600">{{ $user->name }}</span>
                                        <span class="block text-xs text-slate-400">{{ $user->email }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="px-5 py-3">
                                <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-600">{{ $user->quiz_attempts_count }}</span>
                            </td>
                            <td class="px-5 py-3 text-slate-500">{{ $user->created_at?->format('M j, Y') }}</td>
                            <td class="px-5 py-3">
                                @if ($user->is_admin)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-700">
                                        <i data-lucide="shield-check" class="h-3.5 w-3.5"></i> Admin
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">Student</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" title="View" class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-slate-100 hover:text-brand-600">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ $user->is_admin ? 'Remove admin' : 'Make admin' }}"
                                                class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-amber-50 hover:text-amber-600">
                                            <i data-lucide="{{ $user->is_admin ? 'shield-off' : 'shield-plus' }}" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete" class="grid h-8 w-8 place-items-center rounded-full text-slate-500 hover:bg-rose-50 hover:text-rose-600">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        {{ $users->links() }}
    </div>
</div>
@endsection
