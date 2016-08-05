import Vue from '~/vue'
import VueRouter from 'vue-router'

import CoreView from '~/views/core.vue'

Vue.use(VueRouter)

const router = new VueRouter({
  history: true,
  linkActiveClass: 'active',
  saveScrollPosition: true
})

router.map({
  '/': {
    component: resolve => resolve(require('~/views/base/student.vue')),
    subRoutes: {
      '/': { name: 'home', component: resolve => resolve(require('~/views/home/student.vue')), title: 'infoexam' },
      '/sign-in': { name: 'signIn', component: resolve => resolve(require('~/views/auth/signIn.vue')), title: 'signIn' },
      '/practice': { name: 'practice', component: resolve => resolve(require('~/views/practice/list.vue')), title: 'practice' },
      '/practice/:name': { name: 'practice.processing', component: resolve => resolve(require('~/views/practice/processing.vue')), title: 'practice' }
    }
  },
  '/admin': {
    component: resolve => resolve(require('~/views/base/admin.vue')),
    subRoutes: {
      '/exams': { name: 'admin.exams', component: resolve => resolve(require('~/views/exam/list.vue')), title: 'exams.index' },
      '/exams/create': { name: 'admin.exams.create', component: resolve => resolve(require('~/views/exam/create.vue')), title: 'exams.create' },
      '/exams/:name/edit': { name: 'admin.exams.edit', component: resolve => resolve(require('~/views/exam/edit.vue')), title: 'exams.edit' },
      '/exams/:name/questions': { name: 'admin.exams.questions', component: resolve => resolve(require('~/views/question/list.vue')), title: 'exams.questions.index' },
      '/exams/:name/questions/create': { name: 'admin.exams.questions.create', component: resolve => resolve(require('~/views/question/create.vue')), title: 'exams.questions.create' },
      '/exams/:name/questions/:uuid/edit': { name: 'admin.exams.questions.edit', component: resolve => resolve(require('~/views/question/edit.vue')), title: 'exams.questions.edit' },
      '/papers': { name: 'admin.papers', component: resolve => resolve(require('~/views/paper/list.vue')), title: 'papers.index' },
      '/papers/create': { name: 'admin.papers.create', component: resolve => resolve(require('~/views/paper/create.vue')), title: 'papers.create' },
      '/papers/:name/edit': { name: 'admin.papers.edit', component: resolve => resolve(require('~/views/paper/edit.vue')), title: 'papers.edit' },
      '/papers/:name/questions': { name: 'admin.papers.questions', component: resolve => resolve(require('~/views/paper/question/list.vue')), title: 'papers.questions.index' },
      '/papers/:name/questions/update': { name: 'admin.papers.questions.update', component: resolve => resolve(require('~/views/paper/question/update.vue')), title: 'papers.questions.update' }
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
