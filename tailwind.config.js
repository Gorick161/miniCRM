// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        forest: {
          950: "#040D12", // Page BG
          900: "#0A1919",
          800: "#183D3D", // Panel / Header
          600: "#2F5E59",
          500: "#5C8374", // Primary
          300: "#93B1A6", // Muted / borders
        },
      },
      boxShadow: {
        panel: "0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22)",
      },
      borderRadius: {
        xl: "12px",
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms')({ strategy: 'class' }), // opt-in Klassen
  ],
}