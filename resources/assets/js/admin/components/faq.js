(function (Vue, $, routerComponents) {
    routerComponents.faqs = {
        index: Vue.extend({
            template: require('../../template/admin/faqs/index.html'),

            data () {
                return {
                    form: {},
                    faqs: []
                };
            },

            methods: {
                openModal (faq) {
                    this.form = (undefined !== faq) ? this.clone(faq) : {};

                    $('#create-or-update').openModal();
                },

                createOrUpdate () {
                    var vm = this;

                    if (undefined === this.form.id) {
                        this.$http.post('/api/v1/faqs', this.form).then(function (response) {
                            vm.httpSuccessHandler(response);

                            vm.faqs.push(response.data);

                            $('#create-or-update').closeModal();
                        }, function (response) {
                            vm.httpErrorHandler(response);
                        });
                    } else {
                        this.$http.patch('/api/v1/faqs/' + this.form.id, this.form).then(function (response) {
                            vm.httpSuccessHandler(response, {
                                action: 'update'
                            });

                            for (var i in vm.faqs) {
                                if (vm.faqs.hasOwnProperty(i) && vm.form.id === vm.faqs[i].id) {
                                    vm.faqs[i].question = vm.form.question;
                                    vm.faqs[i].answer = vm.form.answer;

                                    break;
                                }
                            }

                            $('#create-or-update').closeModal();
                        }, function (response) {
                            vm.httpErrorHandler(response);
                        });
                    }
                },

                destroy (faq) {
                    var vm = this;

                    this.$http.delete('/api/v1/faqs/' + faq.id).then(function (response) {
                        vm.httpSuccessHandler(response, {action: 'delete'});
                        vm.faqs.$remove(faq);
                    });
                }
            },

            created () {
                var vm = this;

                this.$http.get('/api/v1/faqs').then(function (response) {
                    vm.$set('faqs', response.data);
                });
            }
        })
    };
})(Vue, jQuery, routerComponents);
