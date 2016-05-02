import Vue from 'vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core/core.vue'
import SignInView from '../views/signIn/signIn.vue'

Vue.use(VueRouter)

const infoexam = CoreView

const router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

router.map({
  '/': {
    component: Vue.extend({
      template: '<p>Home page.</p>'
    })
  },
  '/sign-in': { component: SignInView }
})

export { infoexam, router }
