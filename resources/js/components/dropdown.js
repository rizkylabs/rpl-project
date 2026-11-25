export function initDropdowns() {
    const dropdowns = [...document.querySelectorAll('[data-dropdown]')];
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('toggle', () => {
            if (dropdown.open) dropdowns.filter(other => other !== dropdown).forEach(other => other.open = false);
        });
        dropdown.addEventListener('focusout', event => {
            if (event.relatedTarget && !dropdown.contains(event.relatedTarget)) dropdown.open = false;
        });
        dropdown.addEventListener('keydown', event => {
            if (event.key === 'Escape' && dropdown.open) {
                dropdown.open = false;
                dropdown.querySelector('summary').focus();
                event.preventDefault();
            }
            if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key) && dropdown.open) {
                const items = [...dropdown.querySelectorAll('.es-dropdown-panel a, .es-dropdown-panel button:not([disabled])')];
                if (!items.length) return;
                const index = items.indexOf(document.activeElement);
                const next = event.key === 'Home' ? 0 : event.key === 'End' ? items.length - 1 : event.key === 'ArrowDown' ? (index + 1) % items.length : (index < 0 ? items.length - 1 : (index - 1 + items.length) % items.length);
                items[next].focus();
                event.preventDefault();
            }
        });
    });
    document.addEventListener('pointerdown', event => dropdowns.forEach(dropdown => { if (!dropdown.contains(event.target)) dropdown.open = false; }));
    document.querySelector('.es-profile a')?.addEventListener('click', () => { document.querySelector('.es-profile').open = false; });
}
