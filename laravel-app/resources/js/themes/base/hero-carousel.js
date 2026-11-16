const SLIDE_MS = 5500;

// Background-only carousel for the page header: cross-fades on its own, with
// no visitor-facing controls by design (Fixes part 2).
export function initHeroCarousel(root) {
    const slides = Array.from(root.querySelectorAll('[data-hero-slide]'));

    if (slides.length <= 1) {
        return null;
    }

    let index = 0;
    let timer = null;

    function show(newIndex) {
        index = (newIndex + slides.length) % slides.length;
        slides.forEach((slide, i) => slide.classList.toggle('is-active', i === index));
    }

    function start() {
        stop();
        timer = window.setInterval(() => show(index + 1), SLIDE_MS);
    }

    function stop() {
        if (timer) {
            window.clearInterval(timer);
            timer = null;
        }
    }

    document.addEventListener('visibilitychange', () => {
        document.hidden ? stop() : start();
    });

    show(0);
    start();

    return { show, start, stop, getIndex: () => index };
}

function initHeroCarousels() {
    document.querySelectorAll('[data-hero-carousel]').forEach(initHeroCarousel);
}

document.addEventListener('DOMContentLoaded', initHeroCarousels);
