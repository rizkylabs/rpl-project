<a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif data-dashboard-link>
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m3 10 9-7 9 7v10H3zM9 20v-7h6v7"/></svg><span>Dashboard</span>
</a>
<a href="{{ route('profile') }}" @if(request()->routeIs('profile*')) aria-current="page" @endif data-account-link>
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20v-2a7 7 0 0 1 14 0v2"/></svg><span>Profile</span>
</a>
