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
                // slametrivai.pen, Opsi B frames (public site). Body text is 9.7:1 on white, brand is 4.6:1 on white.
                ink: '#03234D', // nav pill, dark bands, footer
                navy: '#032D60', // headings on light
                brand: {
                    DEFAULT: '#0176D3',
                    dark: '#014486', // hover, small accent text on panels
                    glow: '#1B96FF', // radial wash on dark bands
                },
                sky: { DEFAULT: '#78B0FD' }, // accent text on ink
                mist: '#D8E6FE', // body text on ink
                line: '#AACBFF',
                panel: '#EEF4FF',
                body: '#444444',

                // Dark "Studio admin" tokens (Admin — Login frames). Only the login and other auth pages use these.
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
                sans: ['Urbanist', ...defaultTheme.fontFamily.sans],
                studio: ['Inter', ...defaultTheme.fontFamily.sans], // auth pages
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            maxWidth: {
                page: '90rem', // the 1440px design frame
            },
        },
    },

    plugins: [forms, typography],
};
