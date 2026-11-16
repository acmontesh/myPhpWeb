const SCRIPT_URL = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=onTurnstileReady';
const root = document.documentElement;

function currentTheme() {
    return root.getAttribute('data-effective-scheme') === 'dark' ? 'dark' : 'light';
}

function renderWidget(holder) {
    if (!window.turnstile) {
        return;
    }

    if (holder.dataset.widgetId) {
        window.turnstile.remove(holder.dataset.widgetId);
    }

    holder.dataset.widgetId = window.turnstile.render(holder, {
        sitekey: holder.dataset.sitekey,
        theme: currentTheme(),
        language: root.lang || 'auto',
        size: 'flexible',
    });
}

function initTurnstile() {
    const holders = Array.from(document.querySelectorAll('[data-turnstile]'));

    if (!holders.length) {
        return;
    }

    window.onTurnstileReady = () => holders.forEach(renderWidget);

    const script = document.createElement('script');
    script.src = SCRIPT_URL;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);

    // Keep the widget matching the visitor's light/dark choice.
    new MutationObserver(() => holders.forEach(renderWidget)).observe(root, {
        attributes: true,
        attributeFilter: ['data-effective-scheme'],
    });
}

document.addEventListener('DOMContentLoaded', initTurnstile);
