(function (Vue, routerComponents) {
    routerComponents.websiteMaintenance = {
        index: Vue.extend({
            template: require('../../template/admin/website-maintenance/index.html'),

            data () {
                return {
                    form: {
                        student: {},
                        exam: {}
                    }
                };
            },

            methods: {
                update () {
                    var vm = this;

                    this.$http.patch('/api/v1/website-maintenance', this.form).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            action: 'update'
                        })
                    }, function (response) {
                        vm.httpErrorHandler(response)
                    });
                }
            },

            created () {
                var vm = this;

                this.$http.get('/api/v1/website-maintenance').then(function (response) {
                    vm.$set('form', response.data);
                });
            }
        })
    };
})(Vue, routerComponents);
