router.map({
    '/': {
        name: 'home',
        component: routerComponents.home
    }
});

router.start(Vue.extend({}), '#infoexam');
