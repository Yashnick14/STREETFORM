// tailwind.config.cjs
const plugin = require('tailwindcss/plugin');

module.exports = {
  content: [
    "./**/*.php",
    "./public/**/*.php",
    "./views/**/*.php",
    "./views/**/*.html",
    "./public/**/*.html",
  ],
  theme: {
    extend: {
      colors: {
        brand: '#121212',
        accent: '#F5F5F5',
      },
      fontFamily: {
        poppins: ['Poppins', 'sans-serif'],
      },
      borderColor: theme => ({
        DEFAULT: theme('colors.gray.200', 'currentColor'),
      }),
    },
  },
  plugins: [
    plugin(({ addUtilities }) => {
      addUtilities({
        '.no-scrollbar': {
          '-ms-overflow-style': 'none',
          'scrollbar-width': 'none',
        },
        '.no-scrollbar::-webkit-scrollbar': { display: 'none' },
      });
    }),
  ],
};

