require('dotenv').config()

const webpack = require('webpack')
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
    publicPath: production ? '/assets/js/' : '/js/',
    filename: '[name].js'
  },

  module: {
    loaders: [
      { test: /\.js$/, loader: ['babel-loader', 'eslint-loader'], exclude: /node_modules/ },
      { test: /\.json$/, loader: 'json-loader' },
      { test: /\.vue$/, loader: ['vue-loader', 'eslint-loader'] }
    ]
  },

  resolve: {
    alias: {
      '~': path.resolve(__dirname, 'resources', 'assets', 'js')
    }
  },

  plugins: [
    require('./build/on-build-webpack'),
    require('./build/extract-text-webpack').instance,
    new webpack.LoaderOptionsPlugin({
      vue: {
        loaders: require('./build/extract-text-webpack').loaders
      }
    }),
    new webpack.EnvironmentPlugin(['NODE_ENV', 'API_PREFIX', 'API_STANDARDS_TREE', 'API_SUBTYPE', 'API_VERSION']),
    new webpack.optimize.CommonsChunkPlugin({ name: 'vendor', filename: 'vendor.js' })
  ],

  devtool: production ? false : 'source-map'
}

if (! production) {
  config.plugins.push(
    require('./build/browser-sync')
  )
} else {
  config.plugins.push(
    new webpack.optimize.OccurrenceOrderPlugin(),
    new webpack.optimize.UglifyJsPlugin({ compress: { warnings: false }, output: { comments: false }})
  )
}

module.exports = config
