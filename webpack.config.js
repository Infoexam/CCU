require('dotenv').config()

const Webpack = require('webpack')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const path = require('path')
const production = process.argv.includes('-p')

process.env.NODE_ENV = production ? 'production' : 'local'

const config = {
  entry: {
    main: './resources/assets/js/main.js',
    vendor: Object.keys(require('./package.json').dependencies)
  },

  resolve: {
    alias: {
      '~': path.resolve(__dirname, 'resources', 'assets', 'js')
    }
  },

  output: {
    path: path.resolve(__dirname, 'public', production ? 'assets' : '', 'js'),
    filename: '[name].js',
    chunkFilename: '[name].min.js',
    sourceMapFilename: '[file].map'
  },

  module: {
    loaders: [
      { test: /\.js$/, loader: 'babel!eslint', exclude: /node_modules/ },
      { test: /\.json$/, loader: 'json' },
      { test: /\.vue$/, loader: 'vue!eslint' }
    ]
  },

  // babel: {
  //   presets: ['es2015'],
  //   plugins: ['transform-runtime']
  // },

  plugins: [
    new Webpack.EnvironmentPlugin(['NODE_ENV', 'API_PREFIX', 'API_STANDARDS_TREE', 'API_SUBTYPE', 'API_VERSION']),
    new Webpack.optimize.CommonsChunkPlugin({ name: 'vendor' }),
    new Webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, comments: false, exclude: /vendor\.js$/ }),
    new BrowserSyncPlugin({ proxy: 'https://infoexam.dev', browser: 'google chrome' })
  ],

  devtool: 'source-map'
}

if (production) {
  Reflect.deleteProperty(config, 'devtool')
}

module.exports = config
