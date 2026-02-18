import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
            clientPort: 5173,
        },
        watch: {
            usePolling: true,
        },
        proxy: {
            // Proxy all requests except Vite's internal requests to Laravel backend
            '': {
                target: 'http://backend:8000',
                changeOrigin: true,
                bypass: function (req, res, options) {
                    // Don't proxy Vite's internal requests (HMR, node_modules, etc.)
                    if (req.url.includes('/@') || req.url.includes('/node_modules/')) {
                        return req.url;
                    }
                },
            },
        },
    },
});
