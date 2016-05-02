import Vue from 'vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core/core.vue'
import StudentHomeView from '../views/home/student.vue'
import SignInView from '../views/signIn/signIn.vue'

Vue.use(VueRouter)

const infoexam = CoreView

const router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

router.map({
  '/': { component: StudentHomeView },
  '/sign-in': { name: 'signIn', component: SignInView }
})

export { router, infoexam }
