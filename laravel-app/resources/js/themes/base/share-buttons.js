const NETWORKS = [
    { key: 'twitter', label: 'Share on X', icon: 'fa-brands fa-x-twitter' },
    { key: 'linkedin', label: 'Share on LinkedIn', icon: 'fa-brands fa-linkedin-in' },
    { key: 'facebook', label: 'Share on Facebook', icon: 'fa-brands fa-facebook-f' },
    { key: 'whatsapp', label: 'Share on WhatsApp', icon: 'fa-brands fa-whatsapp' },
];

function buildShareUrl(network, pageUrl, title) {
    const encodedUrl = encodeURIComponent(pageUrl);
    const encodedTitle = encodeURIComponent(title);

    switch (network) {
        case 'twitter':
            return `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`;
        case 'linkedin':
            return `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
        case 'facebook':
            return `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        case 'whatsapp':
            return `https://wa.me/?text=${encodedTitle}%20${encodedUrl}`;
        default:
            return pageUrl;
    }
}

function initShareButtons() {
    document.querySelectorAll('[data-share-buttons]').forEach((container) => {
        const pageUrl = container.dataset.url;
        const title = container.dataset.title || '';
        let labels = {};

        try {
            labels = JSON.parse(container.dataset.labels || '{}');
        } catch (error) {
            labels = {};
        }

        NETWORKS.forEach((network) => {
            const link = document.createElement('a');
            link.href = buildShareUrl(network.key, pageUrl, title);
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.className = 'share-buttons__link';
            link.setAttribute('aria-label', labels[network.key] || network.label);

            const icon = document.createElement('i');
            icon.className = network.icon;
            icon.setAttribute('aria-hidden', 'true');

            link.appendChild(icon);
            container.appendChild(link);
        });
    });
}

document.addEventListener('DOMContentLoaded', initShareButtons);
