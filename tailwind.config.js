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
                // Values from slametrivai.pen (Redesign, Amber). The amber only passes WCAG on the dark surfaces.
                void: {
                    DEFAULT: '#0B0F17',
                    deep: '#05070B', // lightbox and modal scrim
                },
                surface: {
                    DEFAULT: '#131A26',
                    inset: '#0E131C', // inputs, image slots, alternating sections
                    deep: '#0F1520', // featured case visual
                    raised: '#1B2333', // chips and icon boxes
                    logo: '#1A2230',
                    elevated: '#161E2C', // resume page
                },
                edge: '#1F2937',
                content: {
                    DEFAULT: '#F3F4F6',
                    secondary: '#9CA3AF',
                    body: '#C9CED6', // long-form paragraphs
                    faint: '#6B7280', // counts, hints, copyright
                    ghost: '#4B5563',
                },
                accent: {
                    DEFAULT: '#F2B84B',
                    hover: '#F5C76D',
                    soft: '#F2B84B',
                    deep: '#B8862F',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            maxWidth: {
                page: '90rem', // the 1440px design frame
            },
        },
    },

    plugins: [forms, typography],
};
