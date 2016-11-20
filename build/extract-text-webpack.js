const ExtractTextPlugin = require('extract-text-webpack-plugin')

const loaders = {
  css: ExtractTextPlugin.extract({
    loader: 'css-loader'
  }),
  scss: ExtractTextPlugin.extract({
    loader: ['css-loader', 'sass-loader']
  })
}

const instance = new ExtractTextPlugin({
  filename: 'main.css',
  allChunks: true
})

module.exports = {
  loaders, instance
}
