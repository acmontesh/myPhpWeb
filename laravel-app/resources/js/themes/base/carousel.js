const AUTOPLAY_MS = 7000;

export function initCarousel(root) {
    const track = root.querySelector('[data-carousel-track]');
    const slides = Array.from(root.querySelectorAll('[data-carousel-slide]'));
    const dots = Array.from(root.querySelectorAll('[data-carousel-dot]'));
    const prevBtn = root.querySelector('[data-carousel-prev]');
    const nextBtn = root.querySelector('[data-carousel-next]');
    const controls = root.querySelector('[data-carousel-controls]');
    const total = slides.length;

    if (!track || total <= 1) {
        return null;
    }

    let index = 0;
    let timer = null;

    function goTo(newIndex) {
        // Modulo keeps it an infinite loop in both directions: advancing past
        // the last quote returns to the first, and vice versa.
        index = (newIndex + total) % total;
        track.style.transform = `translateX(-${index * 100}%)`;
        slides.forEach((slide, i) => slide.classList.toggle('is-active', i === index));
        dots.forEach((dot, i) => dot.classList.toggle('is-active', i === index));
    }

    function next() {
        goTo(index + 1);
    }

    function prev() {
        goTo(index - 1);
    }

    function startAutoplay() {
        stopAutoplay();
        root.classList.remove('is-paused');
        timer = window.setInterval(next, AUTOPLAY_MS);
    }

    function stopAutoplay() {
        if (timer) {
            window.clearInterval(timer);
            timer = null;
        }
        root.classList.add('is-paused');
    }

    nextBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        next();
        startAutoplay();
    });

    prevBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        prev();
        startAutoplay();
    });

    dots.forEach((dot, i) => {
        dot.addEventListener('click', (event) => {
            event.preventDefault();
            goTo(i);
            startAutoplay();
        });
    });

    // Pause only while the pointer is over the arrows/dots, not over the whole
    // band — the carousel is full-bleed, so hovering the band is the normal
    // resting state and pausing there stopped autoplay from ever being seen.
    if (controls) {
        controls.addEventListener('mouseenter', stopAutoplay);
        controls.addEventListener('mouseleave', startAutoplay);
    }

    // Browsers throttle timers in background tabs; restart on return so the
    // carousel doesn't come back frozen.
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoplay();
        } else {
            startAutoplay();
        }
    });

    goTo(0);
    startAutoplay();

    return { goTo, next, prev, startAutoplay, stopAutoplay, getIndex: () => index };
}

function initCarousels() {
    document.querySelectorAll('[data-carousel]').forEach(initCarousel);
}

document.addEventListener('DOMContentLoaded', initCarousels);
