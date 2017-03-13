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
      '/profile': { name: 'profile', component: resolve => resolve(require('~/views/user/profile.vue')), title: 'profile' },
      '/practice': { name: 'practice', component: resolve => resolve(require('~/views/practice/list.vue')), title: 'practice' },
      '/practice/:name': { name: 'practice.processing', component: resolve => resolve(require('~/views/practice/processing.vue')), title: 'practice' },
      '/apply': { name: 'apply', component: resolve => resolve(require('~/views/apply/list.vue')), title: 'applies.index' },
      '/apply/notification': { name: 'apply.notification', component: resolve => resolve(require('~/views/apply/notification.vue')), title: 'applies.notification' },
      '/test': { name: 'test', component: resolve => resolve(require('~/views/test/list.vue')), title: 'test' },
      '/test/:code': { name: 'test.processing', component: resolve => resolve(require('~/views/test/processing.vue')), title: 'test' },
      '/test/:code/manage': { name: 'test.manage', component: resolve => resolve(require('~/views/test/manage.vue')), title: 'test' }
    }
  },
  '/admin': {
    component: resolve => resolve(require('~/views/base/admin.vue')),
    subRoutes: {
      '/': { name: 'admin', component: resolve => resolve(require('~/views/home/admin.vue')), title: 'admin' },
      '/users': { name: 'admin.users', component: resolve => resolve(require('~/views/user/list.vue')), title: 'users.index' },
      '/users/:username': { name: 'admin.users.show', component: resolve => resolve(require('~/views/user/show.vue')), title: 'users.index' },
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
      '/papers/:name/questions/update': { name: 'admin.papers.questions.update', component: resolve => resolve(require('~/views/paper/question/update.vue')), title: 'papers.questions.update' },
      '/listings': { name: 'admin.listings', component: resolve => resolve(require('~/views/listing/list.vue')), title: 'listings.index' },
      '/listings/create': { name: 'admin.listings.create', component: resolve => resolve(require('~/views/listing/create.vue')), title: 'listings.create' },
      '/listings/:code/edit': { name: 'admin.listings.edit', component: resolve => resolve(require('~/views/listing/edit.vue')), title: 'listings.edit' },
      '/listings/:code/applies': { name: 'admin.listings.applies', component: resolve => resolve(require('~/views/listing/apply/list.vue')), title: 'listings.applies.index' },
      '/listings/:code/applies/update': { name: 'admin.listings.applies.update', component: resolve => resolve(require('~/views/listing/apply/update.vue')), title: 'listings.applies.update' },
      '/gradings': { name: 'admin.gradings', component: resolve => resolve(require('~/views/grading/list.vue')), title: 'gradings.index' },
      '/gradings/:code': { name: 'admin.gradings.show', component: resolve => resolve(require('~/views/grading/show.vue')), title: 'gradings.show' }
    }
  }
})

router.afterEach(transition => {
  if (! transition.to.hasOwnProperty('title') || null !== transition.to.title) {
    const title = Vue.t(`title.${transition.to.title || 'infoexam'}`)

    if (transition.to.path.startsWith('/admin')) {
      Vue.share.navbar.title.admin = title
    }

    document.title = title

    if (transition.to.title && '/' !== transition.to.path) {
      document.title += ' | ' + Vue.t('title.infoexam')
    }
  }
})

export { router, CoreView as view }
