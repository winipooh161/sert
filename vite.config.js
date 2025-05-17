import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/lk.css',
                'resources/js/app.js',
                'resources/js/sidebar.js',
                'resources/js/pwa.js', // Добавляем скрипт PWA
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // Добавляем алиасы для Bootstrap, если потребуется
            '~bootstrap': 'node_modules/bootstrap',
        },
    },
});
