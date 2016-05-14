process.env.DISABLE_NOTIFIER = true

var elixir = require('laravel-elixir')

require('laravel-elixir-webpack')
require('laravel-elixir-eslint')

var production = elixir.config.production

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
    }
  })
})
