export function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach(toggle => {
        const password = document.getElementById(toggle.getAttribute('aria-controls'));
        if (!password) return;
        toggle.hidden = false;
        toggle.addEventListener('click', () => {
            const visible = password.type === 'password';
            password.type = visible ? 'text' : 'password';
            toggle.querySelector('[data-eye-open]').toggleAttribute('hidden', visible);
            toggle.querySelector('[data-eye-closed]').toggleAttribute('hidden', !visible);
            toggle.setAttribute('aria-pressed', String(visible));
        });
    });
}
