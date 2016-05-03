import Vue from './vueWithResource'
import VueI18n from 'vue-i18n'
import VueLocales from './locales/index'
import VueProgress from 'vue-progressbar'
import VueAuth from './services/auth/index'
import { router, infoexam } from './routes/index'

// Vue i18n config
Vue.use(VueI18n)

Vue.config.lang = 'zh_TW'

Object.keys(VueLocales).forEach(function (lang) {
  Vue.locale(lang, VueLocales[lang])
})

// Vue progress
// Reference: http://hilongjw.github.io/vue-progressbar/
Vue.use(VueProgress)

// Vue auth
Vue.use(VueAuth, router)

// Vue router
router.start(infoexam, 'main')

require(`../sass/app.scss`)
