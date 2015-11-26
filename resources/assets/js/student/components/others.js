routerComponents.home = Vue.extend({
    template: require('../../template/student/home.html'),

    data: function () {
        return {username: '', password: ''};
    },

    methods: {
        signInSubmit: function () {
            this.signIn();
        },

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
    },

    ready: function () {
        // 判斷是否帶有 signIn 參數，如有則頁面載入後即顯示登入框
        if (undefined !== this.$route.query.signIn) {
            setTimeout(function() {
                $('#sign-in-modal').openModal();
                $('#username').focus();
            }, 50);
        }
    }
});
