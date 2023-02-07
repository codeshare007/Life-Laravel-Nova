let mix = require('laravel-mix')

mix.js('resources/js/resource.js', 'dist/js')
   .sass('resources/sass/resource.scss', 'dist/css')
    .webpackConfig({
        resolve: {
            symlinks: false
        }
    })
