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
            colors: {
                arctic: {
                    primary: '#243C4C',
                    secondary: '#5289AD',
                    slate: '#698696',
                    border: '#ACBCBF',
                    surface: '#F4FCFB',
                },
            },
        },
    },

    plugins: [forms],
};
