import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';


export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: 'resources/js/app.ts',
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
    // server: {
    //     host,
    //     hmr: { host: 'localhost' },
    //     https: {
    //         key: fs.readFileSync('certificates/localhost-key.pem'),
    //         cert: fs.readFileSync('certificates/localhost.pem'),
    //     },
    // },
});
