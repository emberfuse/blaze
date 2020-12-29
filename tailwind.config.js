const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    purge: [
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue"
    ],

    darkMode: false, // or 'media' or 'class'

    theme: {
        container: {
            center: true
        },

        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans]
            }
        }
    },

    variants: {
        opacity: ["responsive", "hover", "focus", "disabled"],

        extend: {}
    },

    plugins: [require("@tailwindcss/forms")]
};
