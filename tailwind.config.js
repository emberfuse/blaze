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
            },

            colors: {
                gray: {
                    100: "#F7FAFC",
                    200: "#EDF2F7",
                    300: "#E2E8F0",
                    400: "#CBD5E0",
                    500: "#A0AEC0",
                    600: "#718096",
                    700: "#4A5568",
                    800: "#2D3748",
                    900: "#1A202C"
                },

                blue: {
                    100: "#E6F0FB",
                    200: "#C0D9F5",
                    300: "#9AC2EF",
                    400: "#4F94E2",
                    500: "#0366D6",
                    600: "#035CC1",
                    700: "#023D80",
                    800: "#012E60",
                    900: "#011F40"
                }
            }
        }
    },

    variants: {
        opacity: ["responsive", "hover", "focus", "disabled"],

        extend: {}
    },

    plugins: [require("@tailwindcss/forms")]
};
