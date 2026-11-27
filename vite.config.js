import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/scss/admin.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3's Sass still uses @import and global functions; keep the build output readable.
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls', 'if-function'],
            },
        },
    },
});
