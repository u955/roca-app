import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/console.scss',
                'resources/sass/eval.scss',
                'resources/sass/widget.scss',

                'resources/js/app.js',
                'resources/js/_console.js',
                'resources/js/_eval.js',
                'resources/js/_livechat.js',
            ],
            refresh: true,
        }),
    ],
});
