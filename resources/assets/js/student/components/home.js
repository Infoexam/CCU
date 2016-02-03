(function (Vue, $, routerComponents) {
    routerComponents.master = Vue.extend({
        template: require('../../template/student/master.html'),

        data () {
            return {
                form: {}
            };
        },

        methods: {
            openSignInModel () {
                this.async("$('#sign-in-modal').openModal();$('#username').focus();");
            },

            signIn () {
                var vm = this;

                this.$http.post('/api/v1/auth/sign-in', this.form).then(function (response) {
                    if (response.headers('intended')) {
                        location.href = response.headers('intended');
                    } else {
                        $('#sign-in-modal').closeModal();

                        this.$root.signIn = true;
                    }
                }, function (response) {
                    vm.toastError($.i18n.t((422 === response.status) ? 'auth.failed' : 'tokenMismatch'));
                });
            },

            signOut() {
                this.$http.get('/api/v1/auth/sign-out').then(function (response) {
                    this.$root.signIn = false;
                });
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
        template: require('../../template/student/home.html'),

        data () {
            return {
                announcements: []
            };
        },

        created () {
            var vm = this;

            this.$http.get('/api/v1/announcements').then(function (response) {
                vm.$set('announcements', response.data.data);
            });
        }
    });

    routerComponents.notFound = Vue.extend({
        template: require('../../template/student/not-found.html')
    });
})(Vue, jQuery, routerComponents);
