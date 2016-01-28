router.map({
    '/': {
        component: routerComponents.master,
        subRoutes: {
            '/': {
                name: 'home',
                component: routerComponents.home
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
