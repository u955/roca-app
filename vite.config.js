import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/sass/livechat.scss',
                'resources/sass/index.scss',

                'resources/js/app.js',
                'resources/js/appprocess.js',
                'resources/js/livechat.js'
            ],
            refresh: true,
        }),
    ],
});
