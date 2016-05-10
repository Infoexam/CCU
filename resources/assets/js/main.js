import Vue from './vue'
import VueAuth from './services/auth'
import { router, infoexam } from './routes'

// Vue auth
Vue.use(VueAuth, router)

// Vue router
router.start(infoexam, 'main')

require(`../sass/app.scss`)
