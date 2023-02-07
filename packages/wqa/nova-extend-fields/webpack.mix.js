let mix = require('laravel-mix')
var tailwindcss = require('tailwindcss')

mix.js('resources/js/nova-extend-fields.js', 'dist/js')
  .postCss('resources/css/nova-extend-fields.css', 'dist/css', [
    tailwindcss('tailwind.js'),
  ])
  //.sass('resources/sass/extend-fields.scss', 'dist/css')
  .webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve(__dirname, '../../../resources/js/'),
      },
    },
  })
