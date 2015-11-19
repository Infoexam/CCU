var elixir = require('laravel-elixir');

require('dotenv').load();

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

elixir(function(mix) {
    mix.scripts([
        'admin/main.js',
        'admin/components',
        'admin/router.js'
    ], 'resources/assets/js/compiled/admin.js')
        .browserify('compiled/admin.js');

    if ('true' === process.env.BROWSER_SYNC) {
        mix.browserSync({
            proxy: 'localhost:8000'
        });
    }

    if ('true' === process.env.PHP_UNIT) {
        mix.phpUnit();
    }
});
