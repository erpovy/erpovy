import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './Modules/**/resources/views/**/*.blade.php',
        './Modules/**/app/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                "primary": "#137fec",
                "background-light": "#f6f7f8",
                "background-dark": "#0f172a", // Darker slate for deep space feel
            },
            fontFamily: {
                "display": ["Manrope", "sans-serif"],
                sans: ["Manrope", ...defaultTheme.fontFamily.sans],
            },
            borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
            backgroundImage: {
                'glass-gradient': 'linear-gradient(180deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%)',
                'neon-active': 'radial-gradient(circle at center, rgba(19, 127, 236, 0.15) 0%, rgba(19, 127, 236, 0) 70%)',
                'deep-space': 'radial-gradient(at 0% 0%, #1e293b 0, transparent 50%), radial-gradient(at 50% 0%, #137fec15 0, transparent 50%), radial-gradient(at 100% 0%, #3b0764 0, transparent 50%)'
            },
            boxShadow: {
                'neon': '0 0 10px rgba(19, 127, 236, 0.5), 0 0 20px rgba(19, 127, 236, 0.3)',
                'glass': '0 4px 30px rgba(0, 0, 0, 0.1)',
            }
        },
    },

    plugins: [forms],
};
