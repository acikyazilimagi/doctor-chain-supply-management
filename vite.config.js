import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
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
    resolve: {
        alias: [
            {
                find: 'vue',
                replacement: 'vue/dist/vue.esm-bundler.js',
            },
            {
                find: 'vue-i18n',
                replacement: 'vue-i18n/dist/vue-i18n.cjs.js',
            }
        ]
    },
});
