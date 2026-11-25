(() => {
    if (window.__edusyncLoading) return;
    window.__edusyncLoading = true;
    const forms = new Map(), notices = new Map();
    let navigation = null;
    const connection = () => navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    const slow = () => {
        const c = connection();
        return !!(c && (c.saveData || ['slow-2g', '2g', '3g'].includes(c.effectiveType) || c.rtt >= 450 || (c.downlink > 0 && c.downlink < 1.5)));
    };
    const offline = 'Tidak ada koneksi. Periksa jaringan Anda.';
    const stalled = 'Respons belum diterima. Periksa koneksi sebelum mengulangi tindakan.';
    const hasWire = (el, prefix) => el.getAttributeNames().some(n => n === prefix || n.startsWith(prefix + '.'));
    function clearNotice(form) { notices.get(form)?.remove(); notices.delete(form); }
    function notice(form, message) {
        clearNotice(form);
        const p = document.createElement('p');
        p.className = 'es-form-network-status'; p.setAttribute('role', 'status'); p.textContent = message;
        form.append(p); notices.set(form, p);
    }
    function finishForm(form) {
        const s = forms.get(form);
        if (!s) return;
        clearTimeout(s.timer); s.spinner?.remove();
        if (s.label) s.label.textContent = s.originalLabel;
        if (s.idleIcon) s.idleIcon.hidden = s.iconWasHidden;
        if (s.button) for (const [name, value] of s.attributes) {
            if (value === null) s.button.removeAttribute(name); else s.button.setAttribute(name, value);
        }
        forms.delete(form); clearNotice(form);
    }
    function startForm(form, button) {
        const label = button?.querySelector('[data-submit-label]');
        const idleIcon = button?.querySelector('[data-submit-idle-icon]');
        const s = { button, label, originalLabel: label?.textContent, idleIcon, iconWasHidden: idleIcon?.hidden, attributes: [], stalled: false };
        forms.set(form, s); clearNotice(form);
        if (button) {
            s.attributes = ['aria-busy', 'aria-disabled'].map(n => [n, button.getAttribute(n)]);
            // Do not disable named submitters; their values may be required by the server.
            button.setAttribute('aria-busy', 'true'); button.setAttribute('aria-disabled', 'true');
            if (label) label.textContent = 'Processing...';
            if (idleIcon) idleIcon.hidden = true;
            if (button.tagName === 'BUTTON') {
                s.spinner = document.createElement('span'); s.spinner.className = 'es-submit-spinner';
                s.spinner.setAttribute('aria-hidden', 'true'); button.append(s.spinner);
            }
        }
        s.timer = setTimeout(() => { s.stalled = true; notice(form, navigator.onLine ? stalled : offline); }, 30000);
    }
    function finishNavigation() {
        if (!navigation) return;
        clearTimeout(navigation.timer); clearTimeout(navigation.timeout); navigation.panel?.remove();
        if (navigation.main) {
            if (navigation.originalBusy === null) navigation.main.removeAttribute('aria-busy');
            else navigation.main.setAttribute('aria-busy', navigation.originalBusy);
        }
        navigation = null;
    }
    const content = () => document.querySelector('.es-main, #fi-main-content');
    function renderNavigation() {
        if (!navigation || forms.size) return;
        const s = navigation, main = content();
        if (!main) return;
        if (!s.panel?.isConnected) {
            s.main = main; s.originalBusy = main.getAttribute('aria-busy'); main.setAttribute('aria-busy', 'true');
            const panel = document.createElement('section'); panel.className = 'es-content-loader';
            const text = document.createElement('p'); text.setAttribute('role', 'status');
            const shapes = document.createElement('div'); shapes.className = 'es-content-skeleton'; shapes.setAttribute('aria-hidden', 'true');
            for (const count of [2, 3, 3]) {
                const block = document.createElement('div');
                for (let i = 0; i < count; i++) block.append(document.createElement('span'));
                shapes.append(block);
            }
            panel.append(text, shapes); main.prepend(panel); Object.assign(s, { panel, text, shapes });
        }
        s.panel.toggleAttribute('data-saving', slow()); s.shapes.hidden = !navigator.onLine || s.stalled;
        s.text.textContent = !navigator.onLine ? offline : s.stalled ? stalled : 'Loading...';
    }
    function startNavigation() {
        if (navigation || forms.size || !content()) return;
        navigation = { stalled: false };
        navigation.timer = setTimeout(renderNavigation, !navigator.onLine ? 0 : slow() ? 120 : 320);
        navigation.timeout = setTimeout(() => { if (navigation) { navigation.stalled = true; renderNavigation(); } }, 30000);
    }
    function reset() {
        finishNavigation(); for (const f of [...forms.keys()]) finishForm(f); for (const f of [...notices.keys()]) clearNotice(f);
    }
    // Native forms use button feedback only. Livewire actions use native component feedback.
    window.addEventListener('submit', event => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || event.defaultPrevented || hasWire(form, 'wire:submit')) return;
        if ((form.target && form.target !== '_self') || (event.submitter?.formTarget && event.submitter.formTarget !== '_self')) return;
        if (new URL(event.submitter?.getAttribute('formaction') || form.action, location.href).origin !== location.origin) return;
        if (forms.has(form)) { event.preventDefault(); return; }
        if (!navigator.onLine) { event.preventDefault(); notice(form, offline); return; }
        finishNavigation(); startForm(form, event.submitter || form.querySelector('button[type="submit"], button:not([type])'));
    });
    window.addEventListener('click', event => {
        if (!(event.target instanceof Element) || event.defaultPrevented) return;
        if (event.target.closest('[data-es-fi-menu]')) { document.querySelector('.fi-topbar-open-sidebar-btn')?.click(); return; }
        const link = event.target.closest('a[href]');
        if (!link || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.hasAttribute('download') || (link.target && link.target !== '_self') || hasWire(link, 'wire:navigate')) return;
        const url = new URL(link.href, location.href);
        if (url.origin !== location.origin || !['http:', 'https:'].includes(url.protocol)) return;
        if (url.pathname === location.pathname && url.search === location.search && (url.hash || location.hash || link.getAttribute('href').startsWith('#'))) return;
        startNavigation();
    });
    document.addEventListener('livewire:navigate', event => { queueMicrotask(() => { if (!event.defaultPrevented) startNavigation(); }); });
    document.addEventListener('livewire:navigated', reset);
    window.addEventListener('pageshow', reset); window.addEventListener('pagehide', reset);
    window.addEventListener('offline', () => { if (navigation) renderNavigation(); for (const f of forms.keys()) notice(f, offline); });
    window.addEventListener('online', () => {
        if (navigation?.panel) renderNavigation();
        for (const f of [...notices.keys()]) { if (forms.get(f)?.stalled) notice(f, stalled); else clearNotice(f); }
    });
})();
