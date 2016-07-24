import Vue from 'vue'
import VueResource from 'vue-resource'
import VueValidator from 'vue-validator'
import VueProgress from 'vue-progressbar'
import VueI18n from 'vue-i18n'
import VueAuth from './services/auth'
import VueShare from './services/share'

import VueLocales from './locales'
import BrowserLocale from './init/browserLocale'
import HttpHeaders from './init/httpHeaders'
import HttpInterceptor from './init/httpInterceptor'

Vue.use(VueResource)
Vue.use(VueValidator)
Vue.use(VueProgress)
Vue.use(VueI18n)

// All api should prefix with 'api'
Vue.http.options.root = `/${process.env.API_PREFIX}`

// Append extra headers
Object.assign(Vue.http.headers.common, HttpHeaders)

Vue.http.interceptors.push(HttpInterceptor)

// Vue progress bar settings
Vue.prototype.$progress.setHolder({ options: {}})

// Vue i18n default language
Vue.config.lang = VueLocales.hasOwnProperty(BrowserLocale) ? BrowserLocale : 'zh_TW'

// Vue i18n set locales
for (const key of Object.keys(VueLocales)) {
  Vue.locale(key, VueLocales[key])
}

// Custom services must install in the last
Vue.use(VueAuth)
Vue.use(VueShare)

export default Vue
