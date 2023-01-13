const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/livechat.scss', 'public/css')
   .sass('resources/sass/index.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/appprocess.js', 'public/js')
   .js('resources/js/livechat.js', 'public/js');
