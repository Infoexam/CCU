import Vue from 'vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core/core.vue'
import StudentHomeView from '../views/home/student.vue'
import AdminHomeView from '../views/home/admin.vue'
import SignInView from '../views/signIn/signIn.vue'
import ExamList from '../views/exam/list.vue'
import ExamCreate from '../views/exam/create.vue'

Vue.use(VueRouter)

const infoexam = CoreView

const router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

router.map({
  '/': { name: 'home', component: StudentHomeView },
  '/sign-in': { name: 'auth.signIn', component: SignInView },
  '/admin': {
    component: AdminHomeView,
    subRoutes: {
      '/exams': { name: 'admin.exams', component: ExamList },
      '/exams/create': { name: 'admin.exams.create', component: ExamCreate }
    }
  }
})

router.beforeEach(function (transition) {
  transition.next()
})

router.afterEach(function (transition) {
  //
})

export { router, infoexam }
