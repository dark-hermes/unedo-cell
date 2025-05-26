import laravel from 'laravel-vite-plugin';
import {
    defineConfig
} from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/admin/static/js/components/dark.js',
                'resources/admin/static/js/components/toast.js',
                'resources/admin/static/js/components/sweetalert.js',
                'resources/admin/static/js/pages/dashboard.js',
                'resources/admin/css/app.css',
                'resources/admin/css/app-dark.css',
                'resources/js/app.js',
                'resources/admin/js/app.js',
                'resources/admin/static/js/initTheme.js'
            ],
            refresh: true,
        }),
    ],
});
