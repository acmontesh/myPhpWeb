// Live feedback for the header overlay opacity slider: the numeric readout and
// the sample tile update as the range moves, so the value is picked visually
// rather than guessed and saved repeatedly.
function initHeroOverlay() {
    const range = document.querySelector('[data-overlay-range]');

    if (!range) {
        return;
    }

    const output = document.querySelector('[data-overlay-output]');
    const preview = document.querySelector('[data-overlay-preview]');

    const sync = () => {
        const percent = Number(range.value);

        if (output) {
            output.textContent = `${percent}%`;
        }

        if (preview) {
            preview.style.opacity = String(percent / 100);
        }
    };

    range.addEventListener('input', sync);
    sync();
}

document.addEventListener('DOMContentLoaded', initHeroOverlay);
