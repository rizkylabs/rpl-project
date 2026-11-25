<x-edusync.dropdown label="Tema tampilan" class="es-theme" data-theme-dropdown>
    <x-slot:trigger>
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8"/><path d="M12 4a8 8 0 0 1 0 16z" fill="currentColor" stroke="none"/></svg>
        <span data-theme-label>Theme</span>
    </x-slot:trigger>
    <p class="es-dropdown-caption">Theme</p>
    @foreach (['system' => 'System', 'light' => 'Light', 'dark' => 'Dark'] as $value => $label)
        <button type="button" data-theme-option="{{ $value }}" aria-pressed="false">{{ $label }}<span class="es-choice-check" aria-hidden="true">✓</span></button>
    @endforeach
</x-edusync.dropdown>
