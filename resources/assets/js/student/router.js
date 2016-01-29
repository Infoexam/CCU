router.map({
    '/': {
        component: routerComponents.master,
        subRoutes: {
            '/': {
                name: 'home',
                component: routerComponents.home
            },

            '/announcements': {
                name: 'announcements.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.announcements.index
                    },
                    '/:heading': {
                        name: 'announcements.show',
                        component: routerComponents.announcements.show
                    }
                }
            },

            '/faqs': {
                name: 'faqs.index',
                component: routerParentInstance,
                subRoutes: {
                    '/': {
                        component: routerComponents.faqs.index
                    }
                }
            }
        }
    },

    '*': {
        name: 'not-found',
        component: routerComponents.notFound
    }
});

router.start(Vue.extend({
    data () {
        return {
            signIn: '1' === document.querySelector('meta[name="sign-in"]').content
        };
    }
}), '#infoexam');
