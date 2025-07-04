import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
    ],

    // Optional: silence Dart Sass deprecation warnings during building.
    // See not:e https://github.com/twbs/bootstrap/issues/40962.
    css: {
        preprocessorOptions: {
            scss: {
                silenceDeprecations: [
                    'import',
                    // 'mixed-decls',
                    'color-functions',
                    'global-builtin',
                ],
            },
        },
    },
});
