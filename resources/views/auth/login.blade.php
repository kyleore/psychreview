@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12">
    <div class="animate-fade-up rounded-2xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
        <div class="mb-6 text-center">
            <span class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="log-in" class="h-6 w-6"></i>
            </span>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Welcome back</h1>
            <p class="mt-1 text-sm text-slate-500">Log in to continue your board-exam review.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                    placeholder="you@example.com">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                <div x-data="{ show: false }" class="relative">
                    <input id="password" name="password" :type="show ? 'text' : 'password'" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2.5 pr-11 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                        placeholder="••••••••">
                    <button type="button" @click="show = !show" :aria-label="show ? 'Hide password' : 'Show password'"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-slate-600">
                        <i x-show="!show" data-lucide="eye" class="h-5 w-5"></i>
                        <i x-show="show" x-cloak data-lucide="eye-off" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-200">
                Remember me
            </label>
            <button type="submit"
                class="btn-press w-full rounded-lg bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-500/30 transition hover:shadow-lg">
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-brand-600 hover:text-brand-700">Sign up</a>
        </p>
    </div>
</div>
@endsection
