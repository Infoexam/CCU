routerComponents.exam.configs = {
    index: Vue.extend( {
        template: require('../../template/admin/exam/configs/index.html') ,

        data () {
            return {
                form: {
                    allowRoom: [],
                    apply: {}
                }
            };
        },

        methods: {
            update () {
                var vm = this;

                this.$http.patch('/api/v1/exam/configs', this.form).then(function (response) {
                    vm.httpSuccessHandler(response, {action: 'update'});
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        },

        created () {
            var vm = this;

            this.$http.get('/api/v1/exam/configs').then(function (response) {
                vm.$set('form', response.data);
            });
        }
    })
};
