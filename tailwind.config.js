/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        screens: {
            sm: "480px",
            md: "768px",
            lg: "976px",
            xl: "1440px",
        },
        extend: {
            // add primary color
            colors: {
                "primary-red": "#942326",
                "secondary-red": "#EA4B48",
                "katar-green": "#1A4231",
                "katar-cream": "#EDEAE3",
                "katar-dark": "#111111",
            },
        },
    },
    plugins: [require("flowbite/plugin")],
};
