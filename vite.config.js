import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

const backendUrl = process.env.VITE_BACKEND_URL ?? 'http://localhost:8000';
const viteInternalPrefixes = [
    '/@vite',
    '/@fs',
    '/@id',
    '/__vite_ping',
    '/__hmr',
    '/__socket',
    '/@react-refresh',
    '/node_modules',
    '/resources/',
];

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
        port: 3000,
        strictPort: true,
        hmr: {
            host: 'localhost',
            clientPort: 3000,
        },
        watch: {
            usePolling: true,
        },
        proxy: {
            '/': {
                target: backendUrl,
                secure: false,
                bypass(req) {
                    // Let Vite handle its own asset and HMR endpoints; proxy everything else to Laravel
                    if (viteInternalPrefixes.some((prefix) => req.url.startsWith(prefix))) {
                        return req.url;
                    }
                    return null;
                },
            },
        },
    },
});
