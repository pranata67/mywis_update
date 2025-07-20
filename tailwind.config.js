import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    darkMode: 'class', // Ini penting untuk theme switcher
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
