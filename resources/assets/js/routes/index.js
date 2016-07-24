import Vue from '../vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core.vue'
import AdminBaseView from '../views/base/admin.vue'
import StudentBaseView from '../views/base/student.vue'
import StudentHomeView from '../views/home/student.vue'
import SignInView from '../views/signIn/signIn.vue'
import ExamList from '../views/exam/list.vue'
import ExamCreate from '../views/exam/create.vue'
import ExamEdit from '../views/exam/edit.vue'
import PracticeList from '../views/practice/list.vue'
import PracticeProcessing from '../views/practice/processing.vue'
import QuestionList from '../views/question/list.vue'
import QuestionCreate from '../views/question/create.vue'
import QuestionShow from '../views/question/show.vue'
import QuestionEdit from '../views/question/edit.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  history: true,
  linkActiveClass: 'active',
  saveScrollPosition: true
})

router.map({
  '/': {
    component: StudentBaseView,
    subRoutes: {
      '/': { name: 'home', component: StudentHomeView, title: 'infoexam' },
      '/sign-in': { name: 'signIn', component: SignInView, title: 'signIn' },
      '/practice': { name: 'practice', component: PracticeList, title: 'practice' },
      '/practice/:name': { name: 'practice.processing', component: PracticeProcessing, title: 'practice' }
    }
  },
  '/admin': {
    component: AdminBaseView,
    subRoutes: {
      '/exams': { name: 'admin.exams', component: ExamList, title: 'exams.index' },
      '/exams/create': { name: 'admin.exams.create', component: ExamCreate, title: 'exams.create' },
      '/exams/:name/edit': { name: 'admin.exams.edit', component: ExamEdit, title: 'exams.edit' },
      '/exams/:name/questions': { name: 'admin.exams.questions', component: QuestionList, title: 'exams.questions.index' },
      '/exams/:name/questions/create': { name: 'admin.exams.questions.create', component: QuestionCreate, title: 'exams.questions.create' },
      '/exams/:name/questions/:uuid': { name: 'admin.exams.questions.show', component: QuestionShow, title: 'exams.questions.show' },
      '/exams/:name/questions/:uuid/edit': { name: 'admin.exams.questions.edit', component: QuestionEdit, title: 'exams.questions.edit' }
    }
  }
})

router.afterEach(transition => {
  if (! transition.to.hasOwnProperty('title') || null !== transition.to.title) {
    const title = Vue.t(`title.${transition.to.title || 'infoexam'}`)

    document.title = title

    if (transition.to.path.startsWith('/admin')) {
      Vue.share.navbar.title.admin = title
    }
  }
})

export { router, CoreView as view }
