import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            container: {
                center: false, // 不自動居中
                center: true,
                padding: '0rem',
                screens: {
                    'sm': '100vw',
                    'md': '100vw',
                    'lg': '100vw',
                    'xl': '100vw',
                    '2xl': '100vw',
                },
            },
        },
    },

    plugins: [forms],
};
