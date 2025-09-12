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
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        brand: {
          50:  '#eef8ff',
          100: '#d9efff',
          200: '#b6e0ff',
          300: '#84caff',
          400: '#52b2fe',
          500: '#2a99f0',   // Primary
          600: '#1f7ed0',
          700: '#1a68aa',
          800: '#185788',
          900: '#153e60',
        },
        ink: {
          50:'#f8fafc',100:'#f1f5f9',200:'#e2e8f0',300:'#cbd5e1',
          400:'#94a3b8',500:'#64748b',600:'#475569',700:'#334155',
          800:'#1f2937',900:'#0f172a'
        },
      },
      boxShadow: {
        card: '0 2px 8px rgba(15, 23, 42, 0.06)',
        cardHover: '0 8px 24px rgba(15, 23, 42, 0.09)',
      },
      borderRadius: {
        xl: '0.9rem',
        '2xl': '1.25rem',
      },
      spacing: {
        '18': '4.5rem',
      },
    },
  },
  plugins: [forms],
};
