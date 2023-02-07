let mix = require('laravel-mix')
var tailwindcss = require('tailwindcss')

mix.js('resources/js/sortable-table-resource.js', 'dist/js')
    .postCss('resources/css/sortable-table-resource.css', 'dist/css', [
      tailwindcss('tailwind.js'),
    ])
    //.sass('resources/sass/sortable-table-resource.scss', 'dist/css')
    .webpackConfig({
        resolve: {
            symlinks: false
        }
    })
