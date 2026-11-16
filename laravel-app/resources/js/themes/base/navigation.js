const STORAGE_KEY = 'color-scheme';

function initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');

    if (!toggle || !panel) {
        return;
    }

    toggle.addEventListener('click', (event) => {
        event.preventDefault();

        const isOpen = panel.classList.toggle('is-open');
        toggle.classList.toggle('is-opened', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Tapping a link on mobile should close the panel behind it.
    panel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            panel.classList.remove('is-open');
            toggle.classList.remove('is-opened');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
}

function initStickyHeader() {
    const header = document.querySelector('[data-site-header]');

    if (!header) {
        return;
    }

    const panel = document.querySelector('[data-nav-panel]');
    let lastY = window.scrollY;

    const update = () => {
        const y = window.scrollY;
        const menuOpen = panel?.classList.contains('is-open');

        header.classList.toggle('is-stuck', y > 8);

        if (Math.abs(y - lastY) > 4) {
            header.classList.toggle('is-hidden', y > lastY && y > 320 && !menuOpen);
            lastY = y;
        }
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
}

function readStoredScheme() {
    try {
        return localStorage.getItem(STORAGE_KEY);
    } catch (error) {
        return null;
    }
}

function storeScheme(scheme) {
    try {
        localStorage.setItem(STORAGE_KEY, scheme);
    } catch (error) {
        // Private browsing with storage disabled — the toggle still works for
        // this page view, it just won't be remembered.
    }
}

function initSchemeToggle() {
    const toggle = document.querySelector('[data-scheme-toggle]');
    const root = document.documentElement;

    if (!toggle) {
        return;
    }

    toggle.addEventListener('click', (event) => {
        event.preventDefault();

        const natural = root.getAttribute('data-theme-mode') === 'dark' ? 'dark' : 'light';
        const current = readStoredScheme() || natural;
        const next = current === 'dark' ? 'light' : 'dark';

        root.setAttribute('data-scheme', next);
        root.setAttribute('data-effective-scheme', next);
        storeScheme(next);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initStickyHeader();
    initSchemeToggle();
});
