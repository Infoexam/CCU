router.map({
    '/': {
        name: 'home',
        component: routerComponents.home
    },

    '/exam/sets': {
        name: 'exam.sets.index',
        component: routerComponents.exam.sets.index
    },
    '/exam/sets/create': {
        name: 'exam.sets.create',
        component: routerComponents.exam.sets.create
    },
    '/exam/sets/:id': {
        name: 'exam.sets.show',
        component: routerComponents.exam.sets.show
    },
    '/exam/sets/:id/edit': {
        name: 'exam.sets.edit',
        component: routerComponents.exam.sets.edit
    },

    '/exam/papers': {
        name: 'exam.papers.index',
        component: routerComponents.exam.papers.index
    },
    '/exam/papers/:id': {
        name: 'exam.papers.show',
        component: routerComponents.exam.papers.show
    },

    '/exam/lists': {
        name: 'exam.lists.index',
        component: routerComponents.exam.lists.index
    },
    '/exam/lists/:id': {
        name: 'exam.lists.show',
        component: routerComponents.exam.lists.show
    },

    '/announcements': {
        name: 'announcements.index',
        component: routerComponents.announcements.index
    },
    '/announcements/create': {
        name: 'announcements.create',
        component: routerComponents.announcements.create
    },
    '/announcements/:heading': {
        name: 'announcements.show',
        component: routerComponents.announcements.show
    },
    '/announcements/:heading/edit': {
        name: 'announcements.edit',
        component: routerComponents.announcements.edit
    },

    '/website-maintenance': {
        name: 'website-maintenance.index',
        component: routerComponents.websiteMaintenance.index
    },

    '/ip-rules': {
        name: 'ip-rules.index',
        component: routerComponents.ipRules.index
    },

    '*' : {
        name: 'not-found',
        component: routerComponents.notFound
    }
});

router.start(Vue.extend({}), '#infoexam');
