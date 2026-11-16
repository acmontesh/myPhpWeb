function initThemePreview() {
    const modal = document.querySelector('[data-theme-preview-modal]');

    if (!modal) {
        return;
    }

    const iframe = modal.querySelector('iframe');
    const titleEl = modal.querySelector('[data-theme-preview-title]');
    const closeBtn = modal.querySelector('[data-theme-preview-close]');

    function closeModal() {
        modal.classList.remove('is-open');
        iframe.src = 'about:blank';
    }

    document.querySelectorAll('[data-theme-preview-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            iframe.src = trigger.dataset.previewUrl;
            titleEl.textContent = `${trigger.dataset.previewName} — preview`;
            modal.classList.add('is-open');
        });
    });

    closeBtn?.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
}

document.addEventListener('DOMContentLoaded', initThemePreview);
