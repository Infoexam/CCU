routerComponents.home = Vue.extend({
    template: require('../../template/student/home.html'),

    data: function () {
        return {username: '', password: ''};
    },

    methods: {
        signIn: function () {
            this.$http.post('/api/v1/auth/sign-in', {
                username: this.username,
                password: this.password
            }, function (data, status, request) {
                window.location.href = (null !== data.Intended) ? data.Intended : '/';
            }).error(function (data, status, request) {
                Materialize.toast(data.errors.auth, 3000, 'red');
            });
        }
    }
});
