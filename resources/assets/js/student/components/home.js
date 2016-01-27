(function (Vue, $, routerComponents) {
    routerComponents.master = Vue.extend({
        template: require('../../template/student/master.html'),

        data () {
            return {
                username: '',
                password: ''
            };
        },

        methods: {
            openSignInModel () {
                "use strict";

                this.async("$('#sign-in-modal').openModal();$('#username').focus();");
            },

            signIn () {
                if (this.username.length && this.password.length) {
                    var vm = this;

                    this.$http.post('/api/v1/auth/sign-in', {
                        username: this.username,
                        password: this.password
                    }).then(function (response) {
                        window.location.href = response.headers('intended') || '/';
                    }, function (response) {
                        vm.toastError($.i18n.t((422 === response.status) ? 'auth.failed' : 'tokenMismatch'));
                    });
                }
            }
        },

        ready () {
            // 判斷是否帶有 signIn 參數，如有則頁面載入後即顯示登入框
            if (undefined !== this.$route.query.signIn) {
                this.openSignInModel();
            }
        }
    });

    routerComponents.home = Vue.extend({
        template: require('../../template/student/home.html')
    });

    routerComponents.notFound = Vue.extend({
        template: require('../../template/student/not-found.html')
    });
})(Vue, jQuery, routerComponents);
