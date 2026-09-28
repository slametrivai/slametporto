import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Navy base. The yellow palette only passes WCAG on dark (8.1-18.5:1), 1.1-2.2:1 on white.
                void: '#080B12',
                surface: {
                    DEFAULT: '#111722',
                    elevated: '#161E2C',
                },
                edge: '#202938',
                content: {
                    DEFAULT: '#F5F7FA',
                    secondary: '#8B95A7', // 5.5-6.5:1 on the navy surfaces
                },
                // Brand palette. Fill = primary CTA and focus only; soft = accent text; deep = borders/rules.
                accent: {
                    DEFAULT: '#FFFF66',
                    hover: '#FFE566',
                    soft: '#D6D58B',
                    deep: '#B3B347',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
