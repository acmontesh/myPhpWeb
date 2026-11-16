<?php

// Static sidebar structure for the dashboard shell (implementation-plan.md
// Phase 2). Route names not yet backed by a real controller point at
// Route::view() stub pages until their phase lands.

return [
    'top' => [
        ['label' => 'Dashboard', 'icon' => 'fa-solid fa-gauge', 'route' => 'admin.dashboard'],
    ],

    'groups' => [
        'content' => [
            'label' => 'Content',
            'icon' => 'fa-solid fa-layer-group',
            'items' => [
                ['label' => 'Blog', 'icon' => 'fa-solid fa-pen-nib', 'route' => 'admin.blog.index'],
                ['label' => 'Research', 'icon' => 'fa-solid fa-flask', 'route' => 'admin.research.index'],
                ['label' => 'Publications', 'icon' => 'fa-solid fa-book', 'route' => 'admin.publications.index'],
                ['label' => 'News', 'icon' => 'fa-solid fa-newspaper', 'route' => 'admin.news.index'],
                ['label' => 'Awards', 'icon' => 'fa-solid fa-trophy', 'route' => 'admin.awards.index'],
                ['label' => 'Quotes I Like', 'icon' => 'fa-solid fa-quote-left', 'route' => 'admin.quotes.index'],
                ['label' => 'About Me', 'icon' => 'fa-solid fa-user', 'route' => 'admin.settings.about'],
            ],
        ],
        'appearance' => [
            'label' => 'Appearance',
            'icon' => 'fa-solid fa-palette',
            'items' => [
                ['label' => 'Themes', 'icon' => 'fa-solid fa-swatchbook', 'route' => 'admin.themes.index'],
                ['label' => 'Images', 'icon' => 'fa-solid fa-images', 'route' => 'admin.images.index'],
                ['label' => 'Header Carousel', 'icon' => 'fa-solid fa-panorama', 'route' => 'admin.hero-slides.index'],
                ['label' => 'Colors', 'icon' => 'fa-solid fa-fill-drip', 'route' => 'admin.appearance.colors'],
                ['label' => 'Motion & Effects', 'icon' => 'fa-solid fa-wand-magic-sparkles', 'route' => 'admin.appearance.motion'],
            ],
        ],
        'settings' => [
            'label' => 'Settings',
            'icon' => 'fa-solid fa-sliders',
            'items' => [
                ['label' => 'General', 'icon' => 'fa-solid fa-globe', 'route' => 'admin.settings.general'],
                ['label' => 'Sections', 'icon' => 'fa-solid fa-table-cells-large', 'route' => 'admin.settings.sections'],
                ['label' => 'Public Phrases', 'icon' => 'fa-solid fa-quote-right', 'route' => 'admin.settings.phrases.index'],
                ['label' => 'Social Networks', 'icon' => 'fa-solid fa-share-nodes', 'route' => 'admin.settings.social.index'],
                ['label' => 'Email & Notifications', 'icon' => 'fa-solid fa-envelope-open-text', 'route' => 'admin.settings.notifications'],
                ['label' => 'Cloudflare Turnstile', 'icon' => 'fa-solid fa-shield-halved', 'route' => 'admin.settings.turnstile'],
            ],
        ],
    ],
];
