import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import daisyui from 'daisyui';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Geist', ...defaultTheme.fontFamily.sans],
                display: ['Geist', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: 'rgb(var(--tw-primary) / <alpha-value>)',
                secondary: 'rgb(var(--tw-secondary) / <alpha-value>)',
                accent: 'rgb(var(--tw-accent) / <alpha-value>)',
                page: 'rgb(var(--tw-page) / <alpha-value>)',
                surface: 'rgb(var(--tw-surface) / <alpha-value>)',
                ink: 'rgb(var(--tw-ink) / <alpha-value>)',
                muted: 'rgb(var(--tw-muted) / <alpha-value>)',
            },
            boxShadow: {
                soft: '0 18px 55px -28px rgb(13 59 102 / 0.26)',
                card: '0 12px 35px -24px rgb(15 23 42 / 0.34)',
            },
            backgroundImage: {
                'hero-glow': 'radial-gradient(circle at 85% 15%, rgb(var(--tw-secondary) / 0.16), transparent 35%), radial-gradient(circle at 10% 90%, rgb(var(--tw-accent) / 0.16), transparent 28%)',
            },
        },
    },

    plugins: [forms, daisyui],
    daisyui: {
        themes: ['light', 'dark'],
        darkTheme: 'dark',
        base: true,
        styled: true,
        utils: true,
        logs: false,
    },
};
