router.map({
    '/': {
        name: 'home',
        component: routerComponents.home
    }
});

router.redirect({
    '*': '/'
});

router.start(Vue.extend({}), '#infoexam');
