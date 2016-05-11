import Vue from 'vue'
import VueResource from 'vue-resource'
import VueProgress from 'vue-progressbar'
import VueI18n from 'vue-i18n'
import VueAuth from './services/auth'

import VueLocales from './locales'
import HttpInterceptor from './init/httpInterceptor'

Vue.use(VueResource)
Vue.use(VueProgress)
Vue.use(VueI18n)

/* Vue resource settings */

// All api should prefix with 'api'
Vue.http.options.root = `/api`

// Add X-XSRF-TOKEN header to prevent CSRF
// Reference: https://laravel.com/docs/5.2/routing#csrf-protection
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift())

// Reference: https://github.com/dingo/api/wiki/Making-Requests-To-Your-API
Vue.http.headers.common['Accept'] = `Accept: application/${'localhost' === window.location.hostname ? `x` : `vnd`}.infoexam.v1+json`

Vue.http.interceptors.push(HttpInterceptor)

/* Vue progress bar settings */

Vue.prototype.$progress.setHolder({options: {}})


/* Vue i18n settings */

// Set default language
Vue.config.lang = 'zh_TW'

// Set locales
for (let key of Object.keys(VueLocales)) {
  Vue.locale(key, VueLocales[key])
}

// VueAuth must install after http setting
Vue.use(VueAuth)

export default Vue
