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
                primary: {
                    50: "#e6fff5",
                    100: "#ccffebb",
                    200: "#99ffd6",
                    300: "#66ffc2",
                    400: "#33ffad",
                    500: "#00DD99",   // exact accent
                    600: "#00c987",
                    700: "#00b565",
                    800: "#00a058",
                    900: "#008040",
                    950: "#006633",
                },
                secondary: {
                    50: "#f0ebff",
                    100: "#e1d6ff",
                    200: "#c4a8ff",
                    300: "#a679ff",
                    400: "#9452FF",   // exact secondary
                    500: "#843cff",
                    600: "#7326ff",
                    700: "#6200ff",
                    800: "#5200cc",
                    900: "#41009e",
                },
                dark: "#1A1F36",      // your primary background
            },
            backgroundImage: {
                'hero': 'linear-gradient(135deg, #1A1F36 0%, #0f1429 60%, #2a1f5e 100%)',
            },
            textShadow: {
                'glow': '0 0 15px rgba(0, 221, 153, 0.6)',
                'glow-lg': '0 0 30px rgba(0, 221, 153, 0.8)',
                'glow-sm': '0 0 8px rgba(0, 221, 153, 0.4)',
                'glow-secondary': '0 0 20px rgba(148, 82, 255, 0.5)',
                'none': 'none',
            },
        },
    },

    plugins: [
        forms,
        function ({ addUtilities }) {
            const newUtilities = {
                /* === GLOW SHADOWS (already there) === */
                '.text-shadow-glow': { textShadow: '0 0 15px rgba(0, 221, 153, 0.6)' },
                '.text-shadow-glow-lg': { textShadow: '0 0 30px rgba(0, 221, 153, 0.8)' },
                '.text-shadow-glow-sm': { textShadow: '0 0 8px rgba(0, 221, 153, 0.4)' },
                '.text-shadow-glow-secondary': { textShadow: '0 0 20px rgba(148, 82, 255, 0.5)' },

                /* === NORMAL DROP SHADOWS (new) === */
                '.text-shadow': { textShadow: '2px 2px 0 rgba(0, 0, 0, 0.9)' },
                '.text-shadow-md': { textShadow: '3px 3px 0 rgba(0, 0, 0, 0.8)' },
                '.text-shadow-lg': { textShadow: '4px 4px 0 rgba(0, 0, 0, 0.9)' },
                '.text-shadow-white': { textShadow: '2px 2px 0 rgba(255, 255, 255, 1)' },
                '.text-shadow-primary': { textShadow: '3px 3px 0 rgb(0, 221, 153)' },
                '.text-shadow-offset': { textShadow: '4px 4px 0 rgb(0, 0, 0)' },

                /* === UTILITY === */
                '.text-shadow-none': { textShadow: 'none' },
            }
            addUtilities(newUtilities, ['responsive'])
        }
    ],
};
