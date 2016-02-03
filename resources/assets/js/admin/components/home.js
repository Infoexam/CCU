(function (Vue, routerComponents) {
    routerComponents.master = Vue.extend({
        template: require('../../template/admin/master.html')
    });

    routerComponents.home = Vue.extend({
        template: require('../../template/admin/home.html')
    });

    routerComponents.notFound = Vue.extend({
        template: require('../../template/admin/not-found.html')
    });
})(Vue, routerComponents);
