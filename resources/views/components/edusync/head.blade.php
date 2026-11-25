<meta name="color-scheme" content="light dark">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800" rel="stylesheet">
<script>
    (() => {
        let mode = 'system';
        try { mode = localStorage.getItem('filament:theme:mode') || 'system'; } catch (_) {}
        if (!['light', 'dark', 'system'].includes(mode)) mode = 'system';
        const dark = mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.dataset.theme = dark ? 'dark' : 'light';
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    })();
</script>

@include('components.edusync.loading-assets')
