var routerParentInstance = Vue.extend({template: '<router-view></router-view>'});

router.map({
    '/': {
        name: 'home',
        component: routerComponents.home
    },

    '/exam/sets': {
        name: 'exam.sets.index',
        component: routerParentInstance,
        subRoutes: {
            '/': {
                component: routerComponents.exam.sets.index
            },
            '/create': {
                name: 'exam.sets.create',
                component: routerComponents.exam.sets.create
            },
            '/:id': {
                name: 'exam.sets.show',
                component: routerComponents.exam.sets.show
            },
            '/:id/edit': {
                name: 'exam.sets.edit',
                component: routerComponents.exam.sets.edit
            }
        }
    },

    '/exam/papers': {
        name: 'exam.papers.index',
        component: routerParentInstance,
        subRoutes: {
            '/': {
                component: routerComponents.exam.papers.index
            },
            '/:id': {
                name: 'exam.papers.show',
                component: routerComponents.exam.papers.show
            }
        }
    },

    '/exam/lists': {
        name: 'exam.lists.index',
        component: routerParentInstance,
        subRoutes: {
            '/': {
                component: routerComponents.exam.lists.index
            },
            '/:id': {
                name: 'exam.lists.show',
                component: routerComponents.exam.lists.show
            }
        }
    },

    '/announcements': {
        name: 'announcements.index',
        component: routerParentInstance,
        subRoutes: {
            '/': {
                component: routerComponents.announcements.index
            },
            '/create': {
                name: 'announcements.create',
                component: routerComponents.announcements.create
            },
            '/:heading': {
                name: 'announcements.show',
                component: routerComponents.announcements.show
            },
            '/:heading/edit': {
                name: 'announcements.edit',
                component: routerComponents.announcements.edit
            }
        }
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

router.afterEach(function (transition) {
    var matches = transition.to.matched, i;

    for (i = 0, router.app.breadcrumbs = []; i < matches.length; ++i) {
        if (matches[i].handler.hasOwnProperty('name') && 'home' !== matches[i].handler.name) {
            router.app.breadcrumbs.push({
                name: matches[i].handler.name,
                params: matches[i].params
            });
        }
    }
});

router.start(Vue.extend({
    data: function () {
        return {
            breadcrumbs: []
        };
    }
}), '#infoexam');
