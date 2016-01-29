(function (Vue, routerComponents) {
    routerComponents.faqs = {
        index: Vue.extend({
            template: require('../../template/student/faqs/index.html'),

            data: function () {
                return {
                    faqs: []
                };
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/faqs').then(function (response) {
                    vm.$set('faqs', response.data);
                });
            }
        })
    };
})(Vue, routerComponents);
