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

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
                Popper: ['popper.js', 'default'],
            })
        ]
    };
});

mix.autoload({
    jQuery: 'jquery',
    $: 'jquery',
    jquery: 'jquery'
});


mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/app-custom.js', 'public/js')
    .js('resources/assets/js/front.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/front.scss', 'public/css')
    .copyDirectory('resources/assets/static/images','public/images')
    .browserSync('laradminator.local')
    .version()
    .sourceMaps();
