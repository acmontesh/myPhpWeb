import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/admin/app.scss',
                'resources/js/admin/app.js',
                'resources/js/themes/base/app.js',
                'resources/scss/themes/aurora/app.scss',
                'resources/scss/themes/slate/app.scss',
                'resources/scss/themes/ivory/app.scss',
                'resources/scss/themes/nova/app.scss',
                'resources/scss/themes/graphite/app.scss',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
