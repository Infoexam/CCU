(function (Vue, routerComponents) {
    routerComponents.user = {
        index: Vue.extend({
            template: require('../../template/admin/user/index.html'),

            data () {
                return {
                    codes: [],
                    departments: {},
                    form: {},
                    users: {}
                };
            },

            methods: {
                search () {
                    var vm = this;

                    this.$http.get('/api/v1/users/search', this.form).then(function (response) {
                        vm.$set('users', response.data);
                    });
                }
            },

            created () {
                var vm = this;

                this.$http.get('/api/v1/exam/lists?code=1').then(function (response) {
                    vm.$set('codes', response.data);
                });

                this.$http.get('/api/v1/categories/user.department').then(function (response) {
                    vm.$set('departments', response.data);
                });
            }
        }),

        edit: Vue.extend({
            template: require('../../template/admin/user/edit.html'),

            data () {
                return {
                    form: {},
                    user: {
                        department: {},
                        grade: {}
                    }
                };
            },

            methods: {
                update () {
                    var vm = this;

                    this.$http.patch('/api/v1/users/' + this.$route.params.user, this.form).then(function (response) {
                        vm.httpSuccessHandler(response, {action: 'update'});
                        vm.form.password = vm.form.password_confirmation = '';
                    }, function (response) {
                        vm.httpErrorHandler(response);
                    });
                }
            },

            created () {
                var vm = this;

                this.$http.get('/api/v1/users/' + this.$route.params.user).then(function (response) {
                    vm.$set('user', response.data);

                    var data = vm.clone(response.data),
                        free = {};

                    for (var i in data.certificates) {
                        if (data.certificates.hasOwnProperty(i)) {
                            free[data.certificates[i].category.id] = data.certificates[i].free;
                        }
                    }

                    vm.$set('form', {
                        name: data.name,
                        email: data.email,
                        free: free
                    });
                });
            }
        })
    };
})(Vue, routerComponents);
