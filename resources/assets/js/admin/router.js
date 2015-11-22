router.map({
    '/': {
        name: 'home',
        component: routerComponents.home
    },

    '/exam/sets': {
        name: 'exam.sets.index',
        component: routerComponents.exam.sets.index
    },

    '/exam/sets/:id': {
        name: 'exam.sets.show',
        component: routerComponents.exam.sets.show
    },

    '/exam/papers': {
        name: 'exam.papers.index',
        component: routerComponents.exam.papers.index
    },

    '/exam/papers/:id': {
        name: 'exam.papers.show',
        component: routerComponents.exam.papers.show
    }
});

router.redirect({
    '*': '/'
});

router.start(Vue.extend({}), '#infoexam');
