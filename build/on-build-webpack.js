const exec = require('child_process').exec
const WebpackOnBuildPlugin = require('on-build-webpack')

module.exports = new WebpackOnBuildPlugin(stats => {
  exec('php artisan sri --override')
})
