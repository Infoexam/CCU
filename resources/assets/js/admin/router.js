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
    }
});

router.redirect({
    '*': '/'
});

router.start(Vue.extend({}), '#infoexam');
