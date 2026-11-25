@if (auth()->check())
    <nav class="es-fi-buttonbar" aria-label="Navigasi seluler admin">
        <a href="{{ \Filament\Pages\Dashboard::getUrl(panel: 'admin') }}" @if(request()->routeIs('filament.admin.pages.dashboard')) aria-current="page" @endif>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 10 9-7 9 7v10H3zM9 20v-7h6v7"/></svg><span>Dashboard</span>
        </a>
        <a href="{{ route('profile') }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20v-2a7 7 0 0 1 14 0v2"/></svg><span>Profile</span>
        </a>
        <button type="button" data-es-fi-menu>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg><span>Menu</span>
        </button>
    </nav>
@endif
