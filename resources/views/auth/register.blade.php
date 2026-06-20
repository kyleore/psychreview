@extends('layouts.app')

@section('title', 'Create account')

@section('content')
<div class="mx-auto flex max-w-md flex-col px-4 py-12">
    <div class="animate-fade-up rounded-2xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
        <div class="mb-6 text-center">
            <span class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-violet-600 text-white shadow-lg shadow-brand-500/30">
                <i data-lucide="user-plus" class="h-6 w-6"></i>
            </span>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Create your account</h1>
            <p class="mt-1 text-sm text-slate-500">Sign up to track your progress and study smarter.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-disc space-y-0.5 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-slate-700">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                    placeholder="Juan Dela Cruz">
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                    placeholder="you@example.com">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                    placeholder="At least 6 characters">
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-slate-700">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-200"
                    placeholder="Re-type your password">
            </div>
            <button type="submit"
                class="btn-press w-full rounded-lg bg-gradient-to-r from-brand-600 to-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-brand-500/30 transition hover:shadow-lg">
                Create account
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">Log in</a>
        </p>
    </div>
</div>
@endsection
