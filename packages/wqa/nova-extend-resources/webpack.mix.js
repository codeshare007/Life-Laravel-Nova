let mix = require('laravel-mix')
var tailwindcss = require('tailwindcss')

mix.js('resources/js/nova-extend-resources.js', 'dist/js')
  .postCss('resources/css/nova-extend-resources.css', 'dist/css', [
    tailwindcss('tailwind.js'),
  ])
  .webpackConfig({
      resolve: {
          alias: {
              '@': path.resolve(__dirname, '../../../resources/js/'),
          },
      },
  })
