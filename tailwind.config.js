/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/css/**/*.css',
    './vendor/livewire/flux/**/*.blade.php'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Plus Jakarta Sans',
          'Inter',
          'ui-sans-serif',
          'system-ui',
          '-apple-system',
          'Segoe UI',
          'Roboto',
          'Helvetica Neue',
          'Arial',
          'Noto Sans',
          'sans-serif'
        ],
        mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco']
      },
      colors: {
        brand: {
          DEFAULT: '#06b6d4',
          500: '#06b6d4',
          600: '#0891b2',
          700: '#0e7490'
        },
        'hero-start': '#0f172a',
        'hero-end': '#0e7490',
        progress: {
          start: '#06b6d4',
          end: '#7dd3fc'
        },
        slate: {
          900: '#0f172a'
        }
      }
    }
  }
};
