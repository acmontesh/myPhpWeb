export function initIconPickers() {
    document.querySelectorAll('[data-icon-picker]').forEach((picker) => {
        const input = picker.querySelector('[data-icon-picker-input]');
        const preview = picker.querySelector('[data-icon-picker-preview]');
        const options = picker.querySelectorAll('[data-icon-option]');

        if (!input || !preview) {
            return;
        }

        options.forEach((option) => {
            option.addEventListener('click', (event) => {
                event.preventDefault();

                const icon = option.dataset.iconOption;

                input.value = icon;
                preview.className = icon;

                options.forEach((other) => {
                    const isSelected = other === option;
                    other.classList.toggle('is-selected', isSelected);
                    other.setAttribute('aria-checked', isSelected ? 'true' : 'false');
                });
            });
        });
    });
}
