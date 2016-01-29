(function (Vue, routerComponents) {
    routerComponents.account = {
        index: Vue.extend({
            template: require('../../template/student/account/index.html'),

            data: function () {
                return {
                    account: {
                        certificates: [],
                        department: {},
                        gender: {},
                        grade: {}
                    }
                };
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/account').then(function (response) {
                    vm.$set('account', response.data);
                });
            }
        })
    };
})(Vue, routerComponents);
