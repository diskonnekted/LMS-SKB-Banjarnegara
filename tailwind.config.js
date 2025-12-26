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
                primary: '#FF8A00',       // Orange Energetik
                secondary: '#4ECDC4',     // Turquoise Segar
                tertiary: '#6C5CE7',      // Purple Modern
                background: '#F9F9F9',    // Off-White Netral
                surface: '#FFFFFF',       // Putih Murni
                'text-dark': '#2D3436',   // Charcoal Gelap
                'text-light': '#FFFFFF',  // Putih Murni
                accent: '#FF6B6B',        // Coral Hangat
                success: '#2ECC71',       // Emerald Hijau
                warning: '#FDBB30',       // Sunshine Kuning
                danger: '#E74C3C',        // Crimson Merah
                info: '#3498DB',          // Sky Biru
                'hover-primary': '#FFE0B2',
                'hover-secondary': '#E0F7FA',
                'hover-tertiary': '#D4E6F1',
            },
        },
    },

    plugins: [forms],
};
