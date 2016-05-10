process.env.DISABLE_NOTIFIER = true

var elixir  = require('laravel-elixir')

require('laravel-elixir-webpack')

if (elixir.config.production) {
  elixir.config.publicPath = 'public/assets'
}

var browsers = ['google chrome']
var proxyUrl = 'localhost:8000'

switch (process.platform) {
  case 'linux':
    browsers = ['chromium-browser'];
    break

  case 'darwin':
    proxyUrl = 'infoexam.dev';
    break
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
        { test: /\.css$/, loader: "style-loader!css-loader" },
        { test: /\.scss$/, loaders: ['style', 'css', 'sass?sourceMap'] },
        { test: /\.(png|jpg|gif)$/, loader: 'file' }
      ]
    }
  })

  mix.browserSync({
    proxy: proxyUrl,
    browser: browsers
  });
})
