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
                window.location.href = data.Intended || '/';
            }).error(function (data, status, request) {
                Materialize.toast($.i18n.t((422 === status) ? 'auth.failed' : 'tokenMismatch'), 3500, 'red');
            });
        }
    }
});
