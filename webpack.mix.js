let mix = require('laravel-mix')
let tailwindcss = require('tailwindcss')
//let novaWatcher = require('nova-watcher')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/app.js', 'public')
    .extract([
        'codemirror',
        'chartist',
        'chartist-plugin-tooltips',
        'flatpickr',
        'form-backend-validation',
        'inflector-js',
        'markdown-it',
        'marked',
        'moment',
        'numeral',
        'pikaday',
        'places.js',
        'popper.js',
        'vue-async-computed',
        'vue-clickaway',
        'vue-toasted',
        'trix',
        'vue',
        'vue-router',
        'portal-vue',
        'lodash',
        'moment-timezone',
        'axios',
        'laravel-nova',
    ])
    .setPublicPath('public')
    .postCss('resources/css/app.css', 'public', [tailwindcss('tailwind.js')])
    //.copy('public', '../nova-app/public/vendor/nova')
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve(__dirname, 'resources/js/'),
            },
        },
    })
    .version()

//new novaWatcher(mix); // Create a new instance of nova-watcher and bind your mix instance.
