const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sourceMaps()
    .postCss('resources/css/app.css', 'public/css', [
        require('autoprefixer'),
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .sourceMaps()
    .webpackConfig(require('./webpack.config'))
    .browserSync('preflight.test')
    .version();
