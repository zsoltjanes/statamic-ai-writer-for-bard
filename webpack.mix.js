const mix = require("laravel-mix");

mix.js("resources/js/main.js", "dist/js/main.js")
    .vue({version: 2})
    .webpackConfig(require('./webpack.config'))

