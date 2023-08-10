import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'dots-darker': 'url("data:image/svg+xml,%3Csvg width=\'30\' height=\'30\' viewBox=\'0 0 30 30\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\' fill=\'rgba(37,37,37,0.08)\'/%3E%3C/svg%3E")',
                'dots-lighter': 'url("data:image/svg+xml,%3Csvg width=\'30\' height=\'30\' viewBox=\'0 0 30 30\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z\' fill=\'rgba(239,233,67,0.07)\'/%3E%3C/svg%3E")',
            },
            colors: {
                ash: {
                    200: '#eee',
                    300: '#cfcfcf',
                    400: '#d6d6d6',
                    500: '#a9a9a9',
                    600: '#727272',
                },
                gravel: {
                    200: '#8b8b8b',
                    300: '#727272',
                    400: '#606060',
                    500: '#535353',
                    600: '#4a4a4a',
                    650: '#434343',
                    700: '#3c3c3c',
                    800: '#373737',
                    900: '#252525',
                    950: '#212121',
                },
                maize: {
                    100: '#fdfdec',
                    200: '#fbfbda',
                    300: '#faf8c7',
                    400: '#f5f28e',
                    500: '#f2ee69',
                    600: '#efea43',
                    700: '#dfd92b',
                    900: '#cec812',
                    950: '#a9a40f'
                },
                tangerine: {
                    100: '#ffc6ad',
                    200: '#ffa985',
                    300: '#ff8d5c',
                    400: '#ff7033',
                    500: '#ff540a',
                    600: '#e04300',
                }
            },
            fontSize: {
                'xxs': '.65rem',
            },
            boxShadow: {
                'gravel-blur-sm': '0 0 0 1px rgba(139, 139, 139, .25)',
                'gravel-blur-base': '0 0 0 1px rgba(139, 139, 139, .25)',
                'maize-blur-sm': '0 0 0 1px rgba(250, 248, 199, .5)',
                'maize-blur-base': '0 0 0 3px rgba(250, 248, 199, .5)',
            }
        },
    },

    plugins: [forms, typography],
};
