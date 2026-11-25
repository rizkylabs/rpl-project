@props(['target'])
<button type="button" class="es-password-toggle" data-password-toggle aria-controls="{{ $target }}" aria-label="Tampilkan kata sandi" aria-pressed="false" hidden>
    <svg data-eye-open aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>
    </svg>
    <svg data-eye-closed aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" hidden>
        <path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9 5.4A11 11 0 0 1 12 5c6.4 0 10 7 10 7a16 16 0 0 1-3.3 4.1M6.1 6.1A17 17 0 0 0 2 12s3.6 7 10 7a11 11 0 0 0 5.1-1.3"/>
    </svg>
</button>
