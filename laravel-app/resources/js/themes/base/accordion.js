function expand(details, content) {
    details.open = true;
    const target = content.scrollHeight;
    content.style.height = '0px';

    requestAnimationFrame(() => {
        content.style.height = target + 'px';
    });

    content.addEventListener('transitionend', function handler() {
        content.style.height = 'auto';
        content.removeEventListener('transitionend', handler);
    });
}

function collapse(details, content) {
    const current = content.scrollHeight;
    content.style.height = current + 'px';

    requestAnimationFrame(() => {
        content.style.height = '0px';
    });

    content.addEventListener('transitionend', function handler() {
        details.open = false;
        content.removeEventListener('transitionend', handler);
    });
}

function initAccordions() {
    document.querySelectorAll('[data-accordion]').forEach((details) => {
        const summaryEl = details.querySelector('summary');
        const content = details.querySelector('[data-accordion-content]');

        if (!summaryEl || !content) {
            return;
        }

        summaryEl.addEventListener('click', (event) => {
            event.preventDefault();

            if (details.open) {
                collapse(details, content);
            } else {
                expand(details, content);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initAccordions);
