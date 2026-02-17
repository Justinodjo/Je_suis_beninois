import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                'resources/css/app.css',
                'resources/css/culture.css',
                'resources/css/interview.css',
                'resources/css/patrimoine.css',
                'resources/css/dashboard.css',
                
                // JS
                'resources/js/app.js',
                // 'resources/js/public.js',
                'resources/js/dashboard/init.js',
            ],
            refresh: true,
        }),
    ],
});