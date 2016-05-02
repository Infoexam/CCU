var elixir = require('laravel-elixir')
var webpack = require('webpack')

require('laravel-elixir-webpack')

if (elixir.config.production) {
  elixir.config.publicPath = 'public/assets'
}

elixir.config.notifications = false

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
        { test: /\.woff2?$|\.ttf$|\.eot$|\.svg$/, loader: 'file' },
        { test: /\.scss$/, loaders: ['style', 'css', 'resolve-url', 'sass?sourceMap'] },
        { test: /\.css$/, loaders: ['style', 'css'] },
        { test: /\.(png|jpg|gif)$/, loader: 'file' }
      ],

      plugins: [
        new webpack.ProvidePlugin({
          $: 'jquery'
        })
      ]
    }
  })
})
