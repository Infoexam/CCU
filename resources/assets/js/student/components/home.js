routerComponents.home = Vue.extend({
    template: require('../../template/student/home.html'),

    data: function () {
        return {
            username: '',
            password: ''
        };
    },

    methods: {
        signIn: function () {
            if (this.username.length && this.password.length) {
                var vm = this;

                this.$http.post('/api/v1/auth/sign-in', {
                    username: this.username,
                    password: this.password
                }, function (data, status, request) {
                    window.location.href = data.Intended || '/';
                }).error(function (data, status, request) {
                    vm.toastError($.i18n.t((422 === status) ? 'auth.failed' : 'tokenMismatch'));
                });
            }
        }
    },

    ready: function () {
        // 判斷是否帶有 signIn 參數，如有則頁面載入後即顯示登入框
        if (undefined !== this.$route.query.signIn) {
            this.async("$('#sign-in-modal').openModal();$('#username').focus();");
        }
    }
});
