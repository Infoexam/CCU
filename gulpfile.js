var elixir  = require('laravel-elixir')
var webpack = require('webpack')

require('laravel-elixir-webpack')

if (elixir.config.production) {
  elixir.config.publicPath = 'public/assets'
}

elixir(function (mix) {
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
        { test: /\.scss$/, loaders: ['style', 'css', 'sass?sourceMap'] },
        { test: /\.(png|jpg|gif)$/, loader: 'file' }
      ]
    }
  })

  mix.browserSync({
    proxy: 'localhost:8000',
    browser: ['google chrome']
  });
})
