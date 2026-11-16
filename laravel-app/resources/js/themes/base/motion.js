const root = document.documentElement;
const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

const isMotionOn = () => root.classList.contains('motion-ready');
const parallaxFactor = () => (isMotionOn() ? parseFloat(root.dataset.parallax || '0') || 0 : 0);

function splitText(el) {
    const text = el.textContent.trim();

    if (!text || el.dataset.splitDone) {
        return;
    }

    el.dataset.splitDone = '1';
    el.textContent = '';

    const label = document.createElement('span');
    label.className = 'visually-hidden';
    label.textContent = text;
    el.appendChild(label);

    let index = 0;
    const words = text.split(/\s+/);

    words.forEach((word, wordIndex) => {
        const wordEl = document.createElement('span');
        wordEl.className = 'split-word';
        wordEl.setAttribute('aria-hidden', 'true');

        Array.from(word).forEach((char) => {
            const charEl = document.createElement('span');
            charEl.className = 'split-char';
            charEl.style.setProperty('--i', String(index));
            charEl.textContent = char;
            wordEl.appendChild(charEl);
            index += 1;
        });

        el.appendChild(wordEl);

        if (wordIndex < words.length - 1) {
            el.appendChild(document.createTextNode(' '));
        }
    });
}

function initReveal() {
    const items = document.querySelectorAll('[data-reveal], [data-split]');

    if (isMotionOn()) {
        document.querySelectorAll('[data-split]').forEach(splitText);
    }

    if (!isMotionOn() || !('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-revealed'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0 }
    );

    items.forEach((el) => observer.observe(el));
}

function animateCount(el) {
    const target = parseInt(el.dataset.countTo || '0', 10);
    const formatter = new Intl.NumberFormat(root.lang || 'en');
    const duration = 1800;
    const start = performance.now();

    const step = (now) => {
        const progress = Math.min(1, (now - start) / duration);
        const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
        el.textContent = formatter.format(Math.round(target * eased));

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };

    requestAnimationFrame(step);
}

function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]');

    if (!counters.length || !isMotionOn() || !('IntersectionObserver' in window)) {
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    counters.forEach((el) => {
        el.textContent = '0';
        observer.observe(el);
    });
}

function initTyper() {
    document.querySelectorAll('[data-typer]').forEach((el) => {
        let words = [];

        try {
            words = JSON.parse(el.dataset.words || '[]');
        } catch (error) {
            words = [];
        }

        if (words.length < 2) {
            return;
        }

        if (!isMotionOn()) {
            el.textContent = words.join(' · ');
            return;
        }

        el.closest('.home-hero__roles')?.classList.add('is-typing');

        let wordIndex = 0;
        let charIndex = words[0].length;
        let deleting = true;
        let wait = 2400;

        const tick = () => {
            const word = words[wordIndex];

            if (deleting) {
                charIndex -= 1;
                wait = 38;

                if (charIndex <= 0) {
                    deleting = false;
                    wordIndex = (wordIndex + 1) % words.length;
                    wait = 380;
                }
            } else {
                charIndex += 1;
                wait = 70;

                if (charIndex >= words[wordIndex].length) {
                    deleting = true;
                    wait = 2400;
                }
            }

            el.textContent = (deleting && charIndex <= 0 ? word : words[wordIndex]).slice(0, Math.max(0, charIndex));
            window.setTimeout(tick, wait);
        };

        window.setTimeout(tick, wait);
    });
}

function initScrollEffects() {
    const progressBar = document.querySelector('[data-scroll-progress]');
    const backToTop = document.querySelector('[data-back-to-top]');
    const backToTopRing = document.querySelector('[data-back-to-top-progress]');
    const fixedLayers = Array.from(document.querySelectorAll('[data-parallax-fixed]'));
    const flowLayers = Array.from(document.querySelectorAll('[data-parallax]'));
    const images = Array.from(document.querySelectorAll('[data-parallax-img]'));
    const sideways = Array.from(document.querySelectorAll('[data-scroll-x]'));
    const heroMedia = document.querySelector('[data-hero-media]');
    const heroContent = document.querySelector('[data-hero-content]');
    let ticking = false;

    const update = () => {
        ticking = false;

        const y = window.scrollY;
        const vh = window.innerHeight;
        const max = Math.max(1, document.documentElement.scrollHeight - vh);
        const progress = Math.min(1, y / max);
        const factor = parallaxFactor();

        root.style.setProperty('--scroll-progress', progress.toFixed(4));

        if (progressBar) {
            progressBar.style.transform = `scaleX(${progress})`;
        }

        if (backToTop) {
            backToTop.classList.toggle('is-visible', y > vh * 0.8);
        }

        if (backToTopRing) {
            backToTopRing.style.strokeDashoffset = String(100 - progress * 100);
        }

        if (!factor) {
            return;
        }

        fixedLayers.forEach((el) => {
            el.style.setProperty('--py', `${(y * parseFloat(el.dataset.parallaxFixed) * factor).toFixed(1)}px`);
        });

        if (y < vh * 1.3) {
            heroMedia?.style.setProperty('--py', `${(y * 0.4 * factor).toFixed(1)}px`);

            if (heroContent) {
                heroContent.style.setProperty('--py', `${(y * 0.22 * factor).toFixed(1)}px`);
                heroContent.style.setProperty('--fade', Math.max(0, 1 - y / (vh * 0.85)).toFixed(3));
            }
        }

        const inView = (rect) => rect.bottom > -200 && rect.top < vh + 200;

        flowLayers.forEach((el) => {
            const rect = el.getBoundingClientRect();

            if (inView(rect)) {
                const offset = (rect.top + rect.height / 2 - vh / 2) * parseFloat(el.dataset.parallax) * factor;
                el.style.setProperty('--py', `${(-offset).toFixed(1)}px`);
            }
        });

        images.forEach((img) => {
            const rect = img.parentElement.getBoundingClientRect();

            if (inView(rect)) {
                const ratio = (rect.top + rect.height / 2 - vh / 2) / vh;
                img.style.setProperty('--py', `${(ratio * -8 * factor).toFixed(2)}%`);
            }
        });

        sideways.forEach((el) => {
            const rect = el.parentElement.getBoundingClientRect();

            if (inView(rect)) {
                el.style.setProperty('--sx', `${((rect.top - vh / 2) * parseFloat(el.dataset.scrollX) * factor).toFixed(1)}px`);
            }
        });
    };

    const request = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    };

    window.addEventListener('scroll', request, { passive: true });
    window.addEventListener('resize', request);
    update();
}

function initCursor() {
    const cursor = document.querySelector('[data-cursor]');

    if (!cursor || !finePointer || !isMotionOn()) {
        cursor?.remove();
        return;
    }

    const ring = cursor.querySelector('.cursor__ring');
    const dot = cursor.querySelector('.cursor__dot');
    const label = cursor.querySelector('[data-cursor-text]');
    const interactive = 'a, button, summary, label, select, [data-cursor-label], [role="button"]';
    let mouseX = -100;
    let mouseY = -100;
    let ringX = -100;
    let ringY = -100;

    root.classList.add('has-cursor');

    window.addEventListener('pointermove', (event) => {
        mouseX = event.clientX;
        mouseY = event.clientY;
        dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
        cursor.classList.remove('is-hidden');
    }, { passive: true });

    document.addEventListener('pointerover', (event) => {
        const target = event.target instanceof Element ? event.target : null;
        const labelled = target?.closest('[data-cursor-label]');
        const hovering = Boolean(target?.closest(interactive));
        const typing = Boolean(target?.closest('input, textarea, iframe'));

        cursor.classList.toggle('is-hover', hovering && !labelled);
        cursor.classList.toggle('is-label', Boolean(labelled));
        cursor.classList.toggle('is-text', typing);
        label.textContent = labelled ? labelled.dataset.cursorLabel : '';
    });

    document.addEventListener('pointerout', (event) => {
        if (!event.relatedTarget) {
            cursor.classList.add('is-hidden');
        }
    });

    window.addEventListener('pointerdown', () => cursor.classList.add('is-down'));
    window.addEventListener('pointerup', () => cursor.classList.remove('is-down'));

    const follow = () => {
        ringX += (mouseX - ringX) * 0.18;
        ringY += (mouseY - ringY) * 0.18;
        ring.style.transform = `translate3d(${ringX}px, ${ringY}px, 0)`;
        requestAnimationFrame(follow);
    };

    requestAnimationFrame(follow);
}

function initMagnetic() {
    if (!finePointer || !isMotionOn()) {
        return;
    }

    document.querySelectorAll('[data-magnetic]').forEach((el) => {
        el.addEventListener('pointermove', (event) => {
            const rect = el.getBoundingClientRect();
            const x = event.clientX - rect.left - rect.width / 2;
            const y = event.clientY - rect.top - rect.height / 2;
            el.style.setProperty('--mag-x', `${(x * 0.3).toFixed(1)}px`);
            el.style.setProperty('--mag-y', `${(y * 0.4).toFixed(1)}px`);
        });

        el.addEventListener('pointerleave', () => {
            el.style.setProperty('--mag-x', '0px');
            el.style.setProperty('--mag-y', '0px');
        });
    });
}

function initTilt() {
    if (!finePointer || !isMotionOn()) {
        return;
    }

    document.querySelectorAll('[data-tilt]').forEach((el) => {
        const max = parseFloat(el.dataset.tiltMax || '6');

        el.addEventListener('pointermove', (event) => {
            const rect = el.getBoundingClientRect();
            const px = (event.clientX - rect.left) / rect.width;
            const py = (event.clientY - rect.top) / rect.height;
            el.style.setProperty('--ry', `${((px - 0.5) * max * 2).toFixed(2)}deg`);
            el.style.setProperty('--rx', `${((0.5 - py) * max * 2).toFixed(2)}deg`);
            el.style.setProperty('--gx', `${(px * 100).toFixed(1)}%`);
            el.style.setProperty('--gy', `${(py * 100).toFixed(1)}%`);
            el.classList.add('is-tilting');
        });

        el.addEventListener('pointerleave', () => {
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
            el.classList.remove('is-tilting');
        });
    });
}

function initSpotlight() {
    const hero = document.querySelector('[data-hero]');

    if (!hero || !finePointer || !isMotionOn()) {
        return;
    }

    hero.addEventListener('pointermove', (event) => {
        const rect = hero.getBoundingClientRect();
        hero.style.setProperty('--spot-x', `${event.clientX - rect.left}px`);
        hero.style.setProperty('--spot-y', `${event.clientY - rect.top}px`);
    });
}

function initIntro() {
    const intro = document.querySelector('[data-intro]');

    if (!intro) {
        return;
    }

    try {
        sessionStorage.setItem('intro-seen', '1');
    } catch (error) {
        // Storage blocked: the intro simply plays again next page.
    }

    window.setTimeout(() => intro.remove(), 2600);
}

function initFilters() {
    document.querySelectorAll('[data-filter-group]').forEach((group) => {
        const chips = group.querySelectorAll('[data-filter]');
        const items = group.querySelectorAll('[data-filter-item]');

        chips.forEach((chip) => {
            chip.addEventListener('click', (event) => {
                event.preventDefault();
                const filter = chip.dataset.filter;

                chips.forEach((other) => {
                    const active = other === chip;
                    other.classList.toggle('is-active', active);
                    other.setAttribute('aria-pressed', active ? 'true' : 'false');
                });

                items.forEach((item) => {
                    const show = filter === 'all' || item.dataset.filterItem === filter;
                    item.hidden = !show;

                    if (show) {
                        item.classList.add('is-revealed');
                        item.classList.remove('is-filter-in');
                        void item.offsetWidth;
                        item.classList.add('is-filter-in');
                    }
                });
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initIntro();
    initReveal();
    initCounters();
    initTyper();
    initScrollEffects();
    initCursor();
    initMagnetic();
    initTilt();
    initSpotlight();
    initFilters();
});
