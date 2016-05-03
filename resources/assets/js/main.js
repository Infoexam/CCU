import Vue from 'vue'
import VueResource from 'vue-resource'
import VueI18n from 'vue-i18n'
import VueLocales from './locales/index'
import VueAuth from './services/auth/index'
import { router, infoexam } from './routes/index'


// Vue resource config
Vue.use(VueResource);

let standardsTree = 'localhost' === window.location.hostname ? `x` : `vnd`;
Vue.http.options.root = `/api`;
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());
Vue.http.headers.common['Accept'] = `Accept: application/${standardsTree}.infoexam.v1+json`;


// Vue i18n config
Vue.use(VueI18n)

Vue.config.lang = 'zh_TW'

Object.keys(VueLocales).forEach(function (lang) {
  Vue.locale(lang, VueLocales[lang])
})


// Vue auth
Vue.use(VueAuth, router)


// Vue router
router.start(infoexam, 'main')


require(`../sass/app.scss`)
