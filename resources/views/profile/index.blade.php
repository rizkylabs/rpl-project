@extends('layouts.portal')
@section('title', 'Profile')
@section('page', 'profile')
@section('content')
    <header class="es-profile-heading" data-enter>
        <h1>Profile</h1>
        <p>Kelola informasi akun dan keamanan login Anda.</p>
    </header>

    <div class="es-profile-grid">
        <aside class="es-panel es-profile-summary" aria-labelledby="profile-summary-heading" data-reveal>
            <div class="es-avatar es-profile-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($profileUser->name, 0, 1)) }}</div>
            <h2 id="profile-summary-heading">{{ $profileUser->name }}</h2>
            <p class="es-muted">{{ $profileUser->email }}</p>
            <dl class="es-profile-facts">
                <div><dt>Portal aktif</dt><dd>{{ $portalLabel }}</dd></div>
                <div><dt>Terdaftar sejak</dt><dd>{{ $profileUser->created_at?->locale('id')->translatedFormat('d F Y') ?? 'Tidak tersedia' }}</dd></div>
            </dl>
            <p class="es-profile-hint">Hubungi pengelola akun untuk perubahan role atau hak akses.</p>
        </aside>

        <div class="es-profile-settings">
            <section class="es-panel" aria-labelledby="personal-heading" data-reveal>
                <div class="es-section-heading"><h2 id="personal-heading">Personal information</h2></div>
                <p class="es-profile-description">Perbarui nama dan email yang digunakan untuk login.</p>
                @if (session('profile_status'))
                    <p class="es-alert" role="status">{{ session('profile_status') }}</p>
                @endif
                <form method="POST" action="{{ route('profile.update') }}" class="es-form">
                    @csrf
                    @method('PATCH')
                    <div class="es-profile-fields">
                        <div class="es-field">
                            <label for="profile-name">Nama lengkap</label>
                            <input id="profile-name" name="name" value="{{ old('name', $profileUser->name) }}" autocomplete="name" maxlength="255" required @error('name', 'profile') aria-invalid="true" aria-describedby="profile-name-error" @enderror>
                            @error('name', 'profile')<p class="es-field-error" id="profile-name-error" role="alert">{{ $message }}</p>@enderror
                        </div>
                        <div class="es-field">
                            <label for="profile-email">Email</label>
                            <input id="profile-email" name="email" type="email" value="{{ old('email', $profileUser->email) }}" autocomplete="email" maxlength="255" required @error('email', 'profile') aria-invalid="true" aria-describedby="profile-email-error" @enderror>
                            @error('email', 'profile')<p class="es-field-error" id="profile-email-error" role="alert">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="es-field es-profile-confirm">
                        <label for="profile-current-password">Current password</label>
                        <div class="es-password-wrap">
                            <input id="profile-current-password" type="password" name="current_password" autocomplete="current-password" maxlength="72" required @error('current_password', 'profile') aria-invalid="true" aria-describedby="profile-current-error" @enderror>
                            <x-edusync.password-toggle target="profile-current-password" />
                        </div>
                        @error('current_password', 'profile')<p class="es-field-error" id="profile-current-error" role="alert">{{ $message }}</p>@enderror
                        <p class="es-profile-hint">Diperlukan untuk mengonfirmasi perubahan akun.</p>
                    </div>
                    <div class="es-profile-actions"><button class="es-primary" type="submit"><span data-submit-label>Save changes</span></button></div>
                </form>
            </section>

            <section class="es-panel" aria-labelledby="security-heading" data-reveal>
                <div class="es-section-heading"><h2 id="security-heading">Change password</h2></div>
                <p class="es-profile-description">Gunakan password baru minimal 12 karakter dan berbeda dari password sebelumnya.</p>
                @if (session('security_status'))
                    <p class="es-alert" role="status">{{ session('security_status') }}</p>
                @endif
                <form method="POST" action="{{ route('profile.password') }}" class="es-form">
                    @csrf
                    @method('PUT')
                    <div class="es-field es-profile-confirm">
                        <label for="security-current-password">Current password</label>
                        <div class="es-password-wrap">
                            <input id="security-current-password" name="current_password" type="password" autocomplete="current-password" maxlength="72" required @error('current_password', 'security') aria-invalid="true" aria-describedby="security-current-error" @enderror>
                            <x-edusync.password-toggle target="security-current-password" />
                        </div>
                        @error('current_password', 'security')<p class="es-field-error" id="security-current-error" role="alert">{{ $message }}</p>@enderror
                    </div>
                    <div class="es-profile-fields">
                        <div class="es-field">
                            <label for="security-password">New password</label>
                            <div class="es-password-wrap">
                                <input id="security-password" name="password" type="password" autocomplete="new-password" minlength="12" maxlength="72" required @error('password', 'security') aria-invalid="true" aria-describedby="security-password-error" @enderror>
                                <x-edusync.password-toggle target="security-password" />
                            </div>
                            @error('password', 'security')<p class="es-field-error" id="security-password-error" role="alert">{{ $message }}</p>@enderror
                        </div>
                        <div class="es-field">
                            <label for="security-password-confirmation">Confirm password</label>
                            <div class="es-password-wrap">
                                <input id="security-password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="12" maxlength="72" required>
                                <x-edusync.password-toggle target="security-password-confirmation" />
                            </div>
                        </div>
                    </div>
                    <div class="es-profile-actions"><button class="es-primary" type="submit"><span data-submit-label>Update password</span></button></div>
                </form>
            </section>
        </div>
    </div>
@endsection
