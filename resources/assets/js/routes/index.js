import Vue from 'vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core/core.vue'
import AdminCoreView from '../views/core/admin.vue'
import StudentHomeView from '../views/home/student.vue'
import AdminHomeView from '../views/home/admin.vue'
import SignInView from '../views/signIn/signIn.vue'

Vue.use(VueRouter)

const infoexam = CoreView

const router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

router.map({
  '/': {name: 'home', component: StudentHomeView},
  '/sign-in': {name: 'auth.signIn', component: SignInView},
  '/admin': {
    component: AdminCoreView,
    subRoutes: {
      '/': {name: 'admin', component: AdminHomeView}
    }
  }
})

export { router, infoexam }
