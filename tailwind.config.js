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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: 'var(--p-primary-color)',
                    50: 'var(--p-primary-50)',
                    100: 'var(--p-primary-100)',
                    200: 'var(--p-primary-200)',
                    300: 'var(--p-primary-300)',
                    400: 'var(--p-primary-400)',
                    500: 'var(--p-primary-500)',
                    600: 'var(--p-primary-600)',
                    700: 'var(--p-primary-700)',
                    800: 'var(--p-primary-800)',
                    900: 'var(--p-primary-900)',
                    950: 'var(--p-primary-950)',
                },
            },
        },
    },

    plugins: [forms],
};
