import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                brand: {
                    primary:   '#e8340a',
                    secondary: '#ff6a00',
                    dark:      '#1a1a2e',
                    navy:      '#0f172a',
                    accent:    '#fbbf24',
                    light:     '#fff5f3',
                }
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
