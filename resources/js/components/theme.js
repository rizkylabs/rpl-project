export function initTheme() {
    const themeKey = 'filament:theme:mode';
    const systemTheme = matchMedia('(prefers-color-scheme: dark)');
    const readTheme = () => {
        try { return localStorage.getItem(themeKey) || 'system'; } catch { return 'system'; }
    };
    let theme = readTheme();
    const options = [...document.querySelectorAll('[data-theme-option]')];
    function applyTheme(mode) {
        theme = ['light', 'dark', 'system'].includes(mode) ? mode : 'system';
        const dark = theme === 'dark' || (theme === 'system' && systemTheme.matches);
        document.documentElement.dataset.theme = dark ? 'dark' : 'light';
        document.documentElement.classList.toggle('dark', dark);
        document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        options.forEach(option => option.setAttribute('aria-pressed', String(option.dataset.themeOption === theme)));
        document.querySelectorAll('[data-theme-label]').forEach(label => {
            label.textContent = { system: 'System', light: 'Light', dark: 'Dark' }[theme];
        });
    }
    applyTheme(theme);
    options.forEach(option => option.addEventListener('click', () => {
        const mode = option.dataset.themeOption;
        try { localStorage.setItem(themeKey, mode); } catch { /* Preference remains valid for this page. */ }
        applyTheme(mode);
        const dropdown = option.closest('details');
        dropdown.open = false;
        dropdown.querySelector('summary').focus();
    }));
    systemTheme.addEventListener('change', () => { if (theme === 'system') applyTheme(theme); });
    window.addEventListener('storage', event => { if (event.key === themeKey || event.key === null) applyTheme(readTheme()); });
}
