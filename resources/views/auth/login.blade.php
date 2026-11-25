@extends('layouts.guest')

@section('title', 'Login - EduSync')

@section('content')
<div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold tracking-tight">
            EduSync
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Sistem Monitoring Akademik dan Aktivitas Siswa
        </p>
    </div>

    <h2 class="mb-6 text-lg font-semibold">
        Masuk ke akun Anda
    </h2>

    @if ($errors->any())
        <div role="alert" class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                autocomplete="username"
                placeholder="nama@edusync.test"
                required
                autofocus
                class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-medium">
                Password
            </label>

            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700"
        >
            Masuk
        </button>
    </form>

    <p class="mt-8 text-center text-xs text-slate-400">
        EduSync &middot; Academic Monitoring System
    </p>

</div>
@endsection