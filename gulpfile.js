require('dotenv').load();

if ('true' === process.env.DISABLE_GULP_NOTIFY) {
    process.env.DISABLE_NOTIFIER = true;
}

var elixir = require('laravel-elixir');

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

elixir(function (mix) {
    mix.scripts([
        'general.js',
        'vue-mixin.js',
        'admin/components',
        'admin/router.js'
    ], 'resources/assets/js/compiled/admin.js')
        .scripts([
            'general.js',
            'vue-mixin.js',
            'student/components',
            'student/router.js'
        ], 'resources/assets/js/compiled/student.js')
        .sass([
            'app.scss',
            'admin.scss'
        ])
        .browserify('compiled/admin.js')
        .browserify('compiled/student.js');

    if ('true' === process.env.BROWSER_SYNC) {
        mix.browserSync({
            proxy: 'localhost:8000'
        });
    }

    if ('true' === process.env.PHP_UNIT) {
        mix.phpUnit();
    }

    if (('development' === process.env.APP_ENV) && (undefined !== process.env.FILESYSTEM_LOCAL_ROOT)) {
        mix.copy('public/css', process.env.FILESYSTEM_LOCAL_ROOT + '/css');
        mix.copy('public/js', process.env.FILESYSTEM_LOCAL_ROOT + '/js');
        mix.copy('public/locales', process.env.FILESYSTEM_LOCAL_ROOT + '/locales');
    }
});
