import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/livechat.scss',

                'resources/js/app.js',
                'resources/js/_app.js',
                'resources/js/_livechat.js',
            ],
            refresh: true,
        }),
    ],
});
