function initLandingNav() {
    const sections = document.querySelectorAll('[data-landing-section]');
    const links = document.querySelectorAll('[data-landing-nav-link]');

    if (!sections.length || !links.length) {
        return;
    }

    links.forEach((link) => {
        link.addEventListener('click', (event) => {
            const key = link.dataset.landingNavLink;
            const target = document.getElementById(key);

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    if (!('IntersectionObserver' in window)) {
        return;
    }

    const setActiveLink = (key) => {
        links.forEach((link) => link.classList.remove('is-active'));
        const activeLink = document.querySelector(`[data-landing-nav-link="${key}"]`);

        if (activeLink) {
            activeLink.classList.add('is-active');
        }
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    setActiveLink(entry.target.id);
                }
            });
        },
        { rootMargin: '-40% 0px -55% 0px' }
    );

    sections.forEach((section) => observer.observe(section));
}

document.addEventListener('DOMContentLoaded', initLandingNav);
