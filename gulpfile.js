require('dotenv').load();

if ('true' === process.env.DISABLE_GULP_NOTIFY) {
    process.env.DISABLE_NOTIFIER = true;
}

var elixir = require('laravel-elixir');

if (elixir.config.production) {
    elixir.config.publicPath = 'public/assets';
}

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
            'admin.scss',
            'student.scss'
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
});
