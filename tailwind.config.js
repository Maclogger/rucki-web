import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'my-white': "#F5F5F7",
                'my-darkblue': "#233A5F",
                'my-black': "#2A2A2A",
                'my-gray': "#E5E5E5",
                'my-github-green': "#22C55E",
            }
        },
    },

    plugins: [
        forms,
    ],
};
