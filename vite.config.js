import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import history from 'connect-history-api-fallback';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        // proxy /api to backend to avoid CORS in dev. Target resolved from env or falls back to local artisan server.
        proxy: {
            '/api': {
                target: (process.env.BACKEND_TARGET || process.env.VITE_API_URL || 'http://127.0.0.1:8000'),
                changeOrigin: true,
                secure: false,
            },
        },
        configureServer(server) {
            // serve index.html for unknown routes (SPA fallback)
            server.middlewares.use(history());
        },
    },
});
