const SCOPES = [
    '.blog-post__body',
    '.news-post__body',
    '.research-item__content',
    '.about-content__body',
];

const RESET_MS = 2000;

// Progressive enhancement: wraps each rendered <pre> so a copy button can be
// positioned over it. Skipped entirely when the Clipboard API isn't available,
// so nothing appears that wouldn't work.
function initCodeBlocks() {
    if (!navigator.clipboard) {
        return;
    }

    document.querySelectorAll(SCOPES.map((scope) => `${scope} pre`).join(', ')).forEach((pre) => {
        if (pre.parentElement?.classList.contains('code-block')) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'code-block';
        pre.parentNode.insertBefore(wrapper, pre);
        wrapper.appendChild(pre);

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'code-block__copy';

        const icon = document.createElement('i');
        icon.className = 'fa-regular fa-copy';
        icon.setAttribute('aria-hidden', 'true');

        const label = document.createElement('span');
        label.textContent = 'Copy';

        button.appendChild(icon);
        button.appendChild(label);
        button.setAttribute('aria-label', 'Copy code to clipboard');

        button.addEventListener('click', (event) => {
            event.preventDefault();

            navigator.clipboard.writeText(pre.innerText).then(() => {
                button.classList.add('is-copied');
                icon.className = 'fa-solid fa-check';
                label.textContent = 'Copied';

                window.setTimeout(() => {
                    button.classList.remove('is-copied');
                    icon.className = 'fa-regular fa-copy';
                    label.textContent = 'Copy';
                }, RESET_MS);
            });
        });

        wrapper.appendChild(button);
    });
}

document.addEventListener('DOMContentLoaded', initCodeBlocks);
