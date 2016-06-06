const tree = ('production' === process.env.NODE_ENV) ? 'vnd' : process.env.API_STANDARDS_TREE

export default {
  // https://github.com/dingo/api/wiki/Making-Requests-To-Your-API
  'Accept': `Accept: application/${tree}.${process.env.API_SUBTYPE}.${process.env.API_VERSION}+json`,

  // https://laravel.com/docs/5.2/routing#csrf-protection
  'X-Csrf-Token': document.querySelector('meta[name="csrf-token"]').content
}
