let elixir = require('laravel-elixir');

require('laravel-elixir-vueify');

if (elixir.config.production) {
  elixir.config.publicPath = 'public/assets';
}

elixir.config.notifications = false;

elixir(function (mix) {
  mix.browserify('main.js');
});
