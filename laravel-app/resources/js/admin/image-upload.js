function updatePreview(container, file) {
    if (!file) {
        return;
    }

    const previewEl = container.querySelector('[data-image-upload-preview]');
    const reader = new FileReader();

    reader.onload = () => {
        let img = previewEl.querySelector('img');

        if (!img) {
            img = document.createElement('img');
            previewEl.appendChild(img);
        }

        img.src = reader.result;
    };

    reader.readAsDataURL(file);
}

function initImageUploads() {
    document.querySelectorAll('[data-image-upload]').forEach((container) => {
        const input = container.querySelector('[data-image-upload-input]');
        const dropzone = container.querySelector('[data-image-upload-dropzone]');

        input.addEventListener('change', () => {
            updatePreview(container, input.files[0]);
        });

        ['dragover', 'dragleave', 'drop'].forEach((eventName) => {
            dropzone.addEventListener(eventName, (event) => event.preventDefault());
        });

        dropzone.addEventListener('dragover', () => dropzone.classList.add('is-dragover'));
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('is-dragover'));
        dropzone.addEventListener('drop', (event) => {
            dropzone.classList.remove('is-dragover');
            const file = event.dataTransfer.files[0];

            if (file) {
                input.files = event.dataTransfer.files;
                updatePreview(container, file);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initImageUploads);
