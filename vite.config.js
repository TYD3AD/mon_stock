import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '192.168.1.32', // ← ton IP locale
        port: 5173,
        strictPort: true,
        hmr: {
            host: '192.168.1.32', // ← pour le hot reload
        },
    },
});
