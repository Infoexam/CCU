routerComponents.home = Vue.extend({
    template: require('../../template/student/home.html'),

    data: function () {
        return {username: '', password: ''};
    },

    methods: {
        signIn: function () {
        }
    }
});
