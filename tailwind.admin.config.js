import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

// Dark "Studio admin" tokens from slametrivai.pen (Admin — Dashboard / Admin — Login), scoped to the
// admin bundle. The gray, brand and status scales keep their TailAdmin names so existing admin views
// re-theme without edits: low steps are dark surfaces, high steps are light text, brand is the amber.
// Tinted -50 steps are pre-blended on the card surface so they stay solid behind text.
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/admin/**/*.blade.php',
        './resources/views/components/admin/**/*.blade.php',
        './resources/views/profile/**/*.blade.php',
        './app/Http/Controllers/Admin/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                white: '#131A26', // card surface
                void: '#0B0F17', // page background, inputs, text on amber
                rail: '#0E131C', // sidebar
                brand: {
                    25: '#1A1710', 50: '#2A2418', 100: '#3A3020', 200: '#5C4A24', 300: '#A9823A',
                    400: '#F5C76D', 500: '#F2B84B', 600: '#F2B84B', 700: '#F5C76D', 800: '#B8862F',
                },
                gray: {
                    25: '#0E131C', 50: '#18202E', 100: '#1F2937', 200: '#1F2937', 300: '#2A3445',
                    400: '#6B7280', 500: '#9CA3AF', 600: '#C9CED6', 700: '#D1D5DB', 800: '#F3F4F6', 900: '#05070B',
                },
                success: { 50: '#132A2A', 500: '#34D399', 600: '#34D399', 700: '#6EE7B7' },
                error: { 50: '#2B1D24', 500: '#F87171', 600: '#F87171', 700: '#FCA5A5' },
                warning: { 50: '#2A2418', 500: '#F2B84B', 600: '#F2B84B', 700: '#F5C76D' },
                info: { 50: '#17263A', 500: '#60A5FA', 700: '#93C5FD' },
                // Chart series, validated for the card surface (dataviz validate_palette, dark mode).
                series: { case: '#BD872C', post: '#4A8FE0' },
            },
            fontSize: {
                'theme-xs': ['12px', '18px'],
                'theme-sm': ['14px', '20px'],
                'title-sm': ['26px', '32px'],
            },
            boxShadow: {
                'theme-xs': 'none',
                'theme-lg': '0px 12px 32px rgba(0, 0, 0, 0.5)',
            },
            ringWidth: { 3: '3px' },
            spacing: { 72.5: '18.125rem' },
            zIndex: { 99998: '99998', 99999: '99999' },
        },
    },

    plugins: [forms],
};
