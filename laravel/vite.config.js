import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/css/components.css',
                'resources/css/food-rescue.css',
                'resources/css/forms.css',
                'resources/css/my-donations.css',
                'resources/css/scroll-animation.css',
                'resources/css/volunteer.css',
                'resources/css/wishlist.css',
                'resources/css/admin-dashboard.css',

                'resources/js/app.js',
                'resources/js/main.js',
                'resources/js/scroll-animation.js',
                'resources/js/admin-dashboard.js',
                'resources/js/auth.js',
                'resources/js/bootstrap.js',
                'resources/js/script.js',
                'resources/js/volunteer.js',
                'resources/js/login.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
