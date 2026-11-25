import './bootstrap';
import { initTheme } from './components/theme';
import { initDropdowns } from './components/dropdown';
import { initPasswordToggles } from './components/password-toggle';
import { initMotion } from './components/motion';
import { initLogin } from './login';
import { initDashboard } from './dashboard';
import { initProfile } from './profile';

const pages = { login: initLogin, dashboard: initDashboard, profile: initProfile };

if (document.body.classList.contains('es-app')) {
    initTheme();
    initDropdowns();

    const page = document.body.dataset.page;
    if (Object.hasOwn(pages, page)) {
        pages[page]();
    } else {
        // Shared behavior for a new page until its own module is registered.
        initPasswordToggles();
        initMotion();
    }
    // Native form feedback and adaptive navigation skeletons remain in loading.js.
}
