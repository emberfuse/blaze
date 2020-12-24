const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .sourceMaps()
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer")
    ])
    .sourceMaps()
    .webpackConfig(require("./webpack.config"))
    .browserSync("cratespace.test")
    .version();
