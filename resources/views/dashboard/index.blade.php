@extends('layouts.portal')
@section('title', 'Dashboard ' . $portalLabel)
@section('page', 'dashboard')
@section('content')
    @php
        $roleIntro = match ($portalRole) {
            'teacher' => ['Ruang kerja guru', 'Kelola kegiatan belajar dan pantau perkembangan siswa dari portal guru.', 'Jadwal mengajar, kehadiran, dan penilaian'],
            'student' => ['Ruang belajar siswa', 'Pantau perjalanan belajar Anda melalui portal siswa.', 'Jadwal pelajaran, kehadiran, dan hasil belajar'],
            'parent' => ['Ruang pantau orang tua', 'Ikuti perkembangan akademik anak melalui portal orang tua.', 'Kehadiran, hasil belajar, dan informasi sekolah'],
            'school' => ['Ruang kerja sekolah', 'Pantau kegiatan akademik melalui portal pihak sekolah.', 'Data siswa, guru, dan kegiatan akademik'],
        };
    @endphp
    <section class="es-welcome" aria-labelledby="welcome-heading" data-reveal>
        <div class="es-welcome-copy">
            <p class="es-kicker">{{ $roleIntro[0] }}</p>
            <h1 id="welcome-heading">Selamat datang,<br><span>{{ auth()->user()->name }}</span></h1>
            <p>{{ $roleIntro[1] }}</p>
        </div>
        <div class="es-welcome-date"><span>Hari ini</span><strong>{{ now()->locale('id')->translatedFormat('d F') }}</strong><span>{{ now()->locale('id')->translatedFormat('l, Y') }}</span></div>
    </section>
    <div class="es-dashboard-grid">
        <section class="es-panel es-workspace" aria-labelledby="workspace-heading" data-reveal>
            <div class="es-section-heading"><h2 id="workspace-heading">Ruang akademik</h2><span>Portal {{ $portalLabel }}</span></div>
            <div class="es-empty">
                <h3>Belum ada data akademik</h3>
                <p>{{ $roleIntro[2] }} belum tersedia. Bagian ini akan menampilkan data setelah fitur akademik diaktifkan.</p>
            </div>
        </section>
        <section id="akun" class="es-panel es-account" aria-labelledby="account-heading" data-reveal>
            <div class="es-section-heading"><h2 id="account-heading">Profile</h2><a class="es-inline-link" href="{{ route('profile') }}">Edit profile</a></div>
            <dl>
                <div><dt>Nama lengkap</dt><dd>{{ auth()->user()->name }}</dd></div>
                <div><dt>Alamat email</dt><dd>{{ auth()->user()->email }}</dd></div>
                <div><dt>Portal aktif</dt><dd>{{ $portalLabel }}</dd></div>
            </dl>
        </section>
    </div>
@endsection
