import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss({
            // --- PASTIKAN BAGIAN INI ADA DAN LENGKAP ---
            config: {
                content: [
                    "./resources/**/*.blade.php",

                    "./resources/**/*.js",
                    "./node_modules/flowbite/**/*.js"
                ],
            }
            // --- BATAS ---
        }),
    ],
});
