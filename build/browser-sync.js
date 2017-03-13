const path = require('path')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const basePath = path.resolve(__dirname, '..')

module.exports = new BrowserSyncPlugin({
  proxy: 'http://infoexam.dev',
  host: 'localhost',
  port: 3000,
  files: path.resolve(basePath, 'resources', 'views', 'home.blade.php'),
  // https: {
  //   key: path.resolve(basePath, 'certs', 'server.key'),
  //   cert: path.resolve(basePath, 'certs', 'server.crt')
  // },
  browser: ['google chrome'],
  reloadDelay: 500
})
