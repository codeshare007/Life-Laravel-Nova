let mix = require('laravel-mix')

mix.js('resources/js/nova-sortable-toggle-fields.js', 'dist/js')
   .sass('resources/sass/nova-sortable-toggle-fields.scss', 'dist/css')
    .webpackConfig({
        resolve: {
            symlinks: false
        }
    })
