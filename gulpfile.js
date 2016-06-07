process.env.DISABLE_NOTIFIER = true

const gulp = require('gulp')
const eslint = require('gulp-eslint')
const elixir = require('laravel-elixir')
const webpack = require('webpack')
const production = elixir.config.production

const DotenvPlugin = require('webpack-dotenv-plugin')

require('laravel-elixir-webpack')

elixir.extend('eslint', function (src, options) {
  const paths = new elixir.GulpPaths().src(src)

  new elixir.Task('eslint', function () {
    this.log(paths.src)

    return gulp.src(paths.src.path)
      .pipe(eslint(options || {}))
      .pipe(eslint.format())
      .pipe(eslint.failAfterError())
  }).watch(paths.src.path)
})

if (production) {
  elixir.config.publicPath = 'public/assets'
}

elixir(function (mix) {
  if (! production) {
    var browsers = ['google chrome']
    var proxyUrl = 'localhost:8000'

    switch (process.platform) {
      case 'linux':
        browsers = ['chromium-browser']
        break

      case 'darwin':
        proxyUrl = 'https://infoexam.dev'
        break
    }

    mix.browserSync({
      proxy: proxyUrl,
      browser: browsers
    })

    mix.eslint([
      'resources/assets/js/**/*.js',
      'resources/assets/js/**/*.vue'
    ])
  }

  mix.webpack('main.js', {
    module: {
      loaders: [
        {
          test: /\.js$/,
          loader: 'babel',
          query: {
            presets: ['es2015']
          },
          exclude: /node_modules/
        },

        { test: /\.vue$/, loader: 'vue' },
        { test: /\.css$/, loader: 'style-loader!css-loader' },
        { test: /\.scss$/, loaders: ['style', 'css', 'sass?sourceMap'] },
        { test: /\.(png|jpg|gif)$/, loader: 'file' }
      ]
    },

    plugins: [
      new DotenvPlugin({ sample: './.env.example', path: './.env' }),
      new webpack.DefinePlugin({ 'process.env': { 'NODE_ENV': production ? '"production"' : '"local"' }})
    ]
  })
})
