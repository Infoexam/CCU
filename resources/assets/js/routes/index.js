import Vue from 'vue'
import VueRouter from 'vue-router'

import CoreView from '../views/core/core.vue'
import StudentHomeView from '../views/home/student.vue'
import AdminHomeView from '../views/home/admin.vue'
import SignInView from '../views/signIn/signIn.vue'
import ExamList from '../views/exam/list.vue'
import ExamCreate from '../views/exam/create.vue'
import PracticeList from '../views/practice/list.vue'
import PracticeProcessing from '../views/practice/processing.vue'
import QuestionList from '../views/question/list.vue'
import QuestionCreate from '../views/question/create.vue'
import QuestionShow from '../views/question/show.vue'

Vue.use(VueRouter)

const infoexam = CoreView

const router = new VueRouter({
  history: true,
  saveScrollPosition: true
})

router.map({
  '/': { name: 'home', component: StudentHomeView },
  '/sign-in': { name: 'signIn', component: SignInView },
  '/practice': { name: 'practice', component: PracticeList },
  '/practice/:id': { name: 'practice.processing', component: PracticeProcessing },
  '/admin': {
    component: AdminHomeView,
    subRoutes: {
      '/exams': { name: 'admin.exams', component: ExamList },
      '/exams/create': { name: 'admin.exams.create', component: ExamCreate },
      '/exams/:id/questions': { name: 'admin.exams.questions', component: QuestionList },
      '/exams/:id/questions/create': { name: 'admin.exams.questions.create', component: QuestionCreate },
      '/exams/:id/questions/:uuid': { name: 'admin.exams.questions.show', component: QuestionShow }
    }
  }
})

export { router, infoexam }
