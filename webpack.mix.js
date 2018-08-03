let mix = require('laravel-mix');

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

// mix.react('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');

mix.scripts('node_modules/chosen-js/chosen.jquery.min.js', 'public/js/chosen.js');
mix.styles([
    'node_modules/bootstrap4c-chosen/dist/css/component-chosen.min.css' 
], 'public/css/chosen.css');
