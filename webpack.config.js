require('dotenv').config()

const Webpack = require('webpack')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const WebpackOnBuildPlugin = require('on-build-webpack')
const exec = require('child_process').exec
const path = require('path')
const production = process.argv.includes('-p')

process.env.NODE_ENV = production ? 'production' : 'local'

const config = {
  entry: {
    main: path.resolve(__dirname, 'resources', 'assets', 'js', 'main.js'),
    vendor: Object.keys(require('./package.json').dependencies)
  },

  output: {
    path: path.resolve(__dirname, 'public', production ? 'assets' : '', 'js'),
    filename: 'main.js'
  },

  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel!eslint', exclude: /node_modules/ },
      { test: /\.json$/, loader: 'json' },
      { test: /\.vue$/, loader: 'vue!eslint', exclude: /node_modules/ }
    ]
  },

  resolve: {
    alias: {
      '~': path.resolve(__dirname, 'resources', 'assets', 'js')
    }
  },

  plugins: [
    new BrowserSyncPlugin({ proxy: 'https://infoexam.dev', browser: 'google chrome' }),
    new WebpackOnBuildPlugin(stats => { exec('php artisan sri --override') }),
    new Webpack.EnvironmentPlugin(['NODE_ENV', 'API_PREFIX', 'API_STANDARDS_TREE', 'API_SUBTYPE', 'API_VERSION']),
    new Webpack.optimize.CommonsChunkPlugin({ name: 'vendor', filename: 'vendor.js' }),
    new Webpack.optimize.OccurrenceOrderPlugin(true),
    new Webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, output: { comments: false }})
  ],

  devtool: production ? false : 'source-map'
}

module.exports = config
