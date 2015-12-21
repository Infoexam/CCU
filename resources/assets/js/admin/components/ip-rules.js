(function (Vue, routerComponents) {
    routerComponents.ipRules = {
        index: Vue.extend({
            template: require('../../template/admin/ip-rules/index.html'),

            data: function () {
                return {
                    ipRules: {},
                    form: {
                        ip: '',
                        rules: {student: true}
                    }
                };
            },

            methods: {
                edit: function (key) {
                    this.form = {
                        ip: key,
                        rules: this.clone(this.ipRules[key])
                    };

                    document.getElementById('ip').focus();
                },

                update: function () {
                    var vm = this;

                    this.$http.post('/api/v1/ip-rules', {
                        ip: this.form.ip,
                        rules: this.form.rules
                    }).then(function (response) {
                        vm.httpSuccessHandler(response.data, status, {action: 'update'});
                        Vue.set(vm.ipRules, vm.form.ip, vm.form.rules);
                        vm.form = {ip: '', rules: {student: true}};
                    });
                },

                destroy: function (key) {
                    var vm = this;

                    this.$http.delete('/api/v1/ip-rules', {ip: key}).then(function (response) {
                        vm.httpSuccessHandler(response.data, status, {action: 'delete'});
                        Vue.delete(vm.ipRules, key);
                    });
                }
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/ip-rules').then(function (response) {
                    vm.ipRules = vm.empty(response.data) ? {} : response.data;
                });
            }
        })
    };
})(Vue, routerComponents);
