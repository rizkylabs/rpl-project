@extends('layouts.guest')
@section('title', 'Login - EduSync')
@section('page', 'login')
@section('content')
    <section class="es-auth-story" aria-labelledby="story-heading">
        @include('components.edusync.brand')
        <div class="es-story-copy" data-enter>
            <p class="es-kicker">Sistem monitoring akademik</p>
            <h1 id="story-heading">Ruang akademik<br class="es-desktop-break"> EduSync.</h1>
            <p>Akses portal siswa, guru, orang tua, dan pihak sekolah menggunakan akun terdaftar.</p>
        </div>
        <div class="es-story-footer">
            <span>EduSync</span><span>Ruang akademik digital</span>
        </div>
    </section>
    <section class="es-auth-main" aria-labelledby="login-heading">
        <div class="es-auth-tools">@include('components.edusync.theme')</div>
        <div class="es-login-content" data-enter>
            <h2 id="login-heading">Login</h2>
            <p class="es-muted">Gunakan akun yang terdaftar di EduSync.</p>
            @if ($errors->any())
                <div class="es-alert" role="alert">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('login.store') }}" class="es-form" data-login-form>
                @csrf
                <div class="es-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" placeholder="nama@contoh.id" maxlength="255" required @if($errors->has('email')) aria-invalid="true" @endif>
                </div>
                <div class="es-field">
                    <label for="password">Password</label>
                    <div class="es-password-wrap">
                        <input id="password" name="password" type="password" autocomplete="current-password" maxlength="72" required @if($errors->has('password')) aria-invalid="true" @endif>
                        <x-edusync.password-toggle target="password" />
                    </div>
                </div>
                <button class="es-primary" type="submit" data-login-submit><span data-submit-label>Login</span><span data-submit-idle-icon aria-hidden="true">→</span></button>
            </form>
            <p class="es-login-help">Kesulitan masuk? Hubungi pengelola akun di sekolah Anda.</p>
        </div>
        <footer class="es-auth-footer">© {{ now()->year }} EduSync</footer>
    </section>
@endsection
