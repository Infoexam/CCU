process.env.DISABLE_NOTIFIER = true

const gulp = require('gulp')
const elixir = require('laravel-elixir')
const webpack = require('webpack')
const production = elixir.config.production

const DotenvPlugin = require('webpack-dotenv-plugin')

require('laravel-elixir-eslint');
require('laravel-elixir-webpack')

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

        { test: /\.vue$/, loader: 'vue' }
      ]
    },

    plugins: [
      new DotenvPlugin({ sample: './.env.example', path: './.env' }),
      new webpack.DefinePlugin({ 'process.env': { 'NODE_ENV': production ? '"production"' : '"local"' }}),
      new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }})
    ]
  })
})
