/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'medical-blue': '#0066CC',
                'medical-blue-dark': '#004085',
                'medical-blue-light': '#E6F2FF',
                'medical-white': '#FFFFFF',
            },
            fontFamily: {
                'cairo': ['Cairo', 'sans-serif'],
            },
        },
    },
    plugins: [require("daisyui")],
    daisyui: {
        themes: [
            {
                medical: {
                    "primary": "#0066CC",      // Medical Blue
                    "primary-focus": "#004085", // Darker Medical Blue
                    "primary-content": "#FFFFFF",
                    "secondary": "#0066CC",    // Same blue for consistency
                    "secondary-focus": "#004085",
                    "secondary-content": "#FFFFFF",
                    "accent": "#0066CC",
                    "neutral": "#3D4451",
                    "base-100": "#FFFFFF",     // Pure White
                    "base-200": "#F5F7FA",     // Light Gray-Blue
                    "base-300": "#E5E9F0",     // Lighter Gray-Blue
                    "info": "#0066CC",
                    "success": "#28A745",
                    "warning": "#FFC107",
                    "error": "#DC3545",
                },
            },
        ],
    },
};