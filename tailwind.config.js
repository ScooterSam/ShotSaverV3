const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    purge: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            colors : {
                "smoke" : {
                    900     : "rgba(0, 0, 0, 0.9)",
                    800     : "rgba(0, 0, 0, 0.8)",
                    700     : "rgba(0, 0, 0, 0.7)",
                    600     : "rgba(0, 0, 0, 0.6)",
                    DEFAULT : "rgba(0, 0, 0, 0.5)",
                    400     : "rgba(0, 0, 0, 0.4)",
                    300     : "rgba(0, 0, 0, 0.3)",
                    200     : "rgba(0, 0, 0, 0.2)",
                    100     : "rgba(0, 0, 0, 0.1)",
                    50     : "rgba(0, 0, 0, 0.05)",
                },
                "faded" : {
                    900     : "rgba(255, 255, 255, 0.9)",
                    800     : "rgba(255, 255, 255, 0.8)",
                    700     : "rgba(255, 255, 255, 0.7)",
                    600     : "rgba(255, 255, 255, 0.6)",
                    DEFAULT : "rgba(255, 255, 255, 0.5)",
                    400     : "rgba(255, 255, 255, 0.4)",
                    300     : "rgba(255, 255, 255, 0.3)",
                    200     : "rgba(255, 255, 255, 0.2)",
                    100     : "rgba(255, 255, 255, 0.1)",
                }
            }
            
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
           
        },
        
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
