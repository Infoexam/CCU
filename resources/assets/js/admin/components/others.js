(function (Vue, routerComponents) {
    routerComponents.home = Vue.extend({
        template: require('../../template/admin/home.html')
    });

    routerComponents.notFound = Vue.extend({
        template: require('../../template/admin/not-found.html')
    });
})(Vue, routerComponents);
