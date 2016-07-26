require('dotenv').config()

const path = require('path')
const webpack = require('webpack')
const production = process.argv.includes('-p')

process.env.NODE_ENV = production ? 'production' : 'local'

module.exports = {
  entry: {
    main: './resources/assets/js/main.js',
    vendor: Object.keys(require('./package.json').dependencies)
  },

  output: {
    path: path.join(__dirname, 'public', production ? '/assets/js' : '/js'),
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

  babel: {
    presets: ['es2015'],
    plugins: ['transform-runtime']
  },

  plugins: [
    new webpack.EnvironmentPlugin(['NODE_ENV', 'API_PREFIX', 'API_STANDARDS_TREE', 'API_SUBTYPE', 'API_VERSION']),
    new webpack.optimize.CommonsChunkPlugin({ name: 'vendor' }),
    new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, comments: false, exclude: /vendor\.js$/ })
  ]
}
