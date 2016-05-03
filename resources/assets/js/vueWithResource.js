import Vue from 'vue'
import VueResource from 'vue-resource'

Vue.use(VueResource);

// All api should prefix with 'api'
Vue.http.options.root = `/api`;

// Add X-XSRF-TOKEN header to prevent CSRF
// Reference: https://laravel.com/docs/5.2/routing#csrf-protection
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());

// Reference: https://github.com/dingo/api/wiki/Making-Requests-To-Your-API
Vue.http.headers.common['Accept'] = `Accept: application/${'localhost' === window.location.hostname ? `x` : `vnd`}.infoexam.v1+json`;

export default Vue
