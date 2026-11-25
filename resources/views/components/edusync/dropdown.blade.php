@props(['label', 'align' => 'end'])
<details {{ $attributes->class(['es-dropdown', 'es-dropdown-start' => $align === 'start']) }} data-dropdown>
    <summary class="es-dropdown-trigger" aria-label="{{ $label }}">
        {{ $trigger }}
        <svg class="es-chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
    </summary>
    <div class="es-dropdown-panel">{{ $slot }}</div>
</details>
