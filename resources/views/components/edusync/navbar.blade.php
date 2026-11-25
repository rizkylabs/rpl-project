<header class="es-navbar">
    <div class="es-navbar-brand">
        @include('components.edusync.brand')
        <span class="es-portal-label">{{ $portalLabel }}</span>
    </div>
    <nav class="es-nav es-nav-desktop" aria-label="Menu utama">
        @include('components.edusync.nav-links')
    </nav>
    <div class="es-navbar-tools">
        @include('components.edusync.theme')
        <x-edusync.dropdown label="Menu akun" class="es-profile">
            <x-slot:trigger>
                <span class="es-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="es-profile-name">{{ auth()->user()->name }}</span>
            </x-slot:trigger>
            <div class="es-dropdown-user"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></div>
            <a href="{{ route('profile') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><span data-submit-label>Logout</span></button></form>
        </x-edusync.dropdown>
    </div>
</header>
<nav class="es-nav es-buttonbar" aria-label="Navigasi seluler">
    @include('components.edusync.nav-links')
</nav>
