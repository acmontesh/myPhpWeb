function initColorPicker() {
    const modal = document.querySelector('[data-color-modal]');

    if (!modal) {
        return;
    }

    const openBtn = document.querySelector('[data-color-modal-open]');
    const closeBtn = modal.querySelector('[data-color-modal-close]');
    const hiddenInput = document.querySelector('[data-accent-input]');
    const preview = document.querySelector('[data-accent-preview]');

    openBtn?.addEventListener('click', (event) => {
        event.preventDefault();
        modal.classList.add('is-open');
    });

    closeBtn?.addEventListener('click', () => modal.classList.remove('is-open'));

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.remove('is-open');
        }
    });

    modal.querySelectorAll('[data-color-option]').forEach((option) => {
        option.addEventListener('click', (event) => {
            event.preventDefault();
            const color = option.dataset.colorOption;

            hiddenInput.value = color;
            preview.style.backgroundColor = color;

            modal.querySelectorAll('[data-color-option]').forEach((btn) => {
                btn.classList.toggle('is-selected', btn === option);
            });

            modal.classList.remove('is-open');
        });
    });
}

document.addEventListener('DOMContentLoaded', initColorPicker);
