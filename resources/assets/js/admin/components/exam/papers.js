routerComponents.exam.papers = {
    index: Vue.extend({
        template: require('../../template/admin/exam/papers/index.html'),

        data () {
            return {
                papers: [],
                pagination: {}
            };
        },

        methods: {
            paginate (next) {
                var url = '/api/v1/exam/papers';

                if ('undefined' !== typeof next) {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                if ('string' === typeof url) {
                    var vm = this;

                    this.$http.get(url).then(function (response) {
                        vm.$set('papers', response.data.data);

                        delete response.data.data;
                        vm.$set('pagination', response.data);
                    });
                }
            },

            destroy (paper) {
                var vm = this;

                this.$http.delete('/api/v1/exam/papers/' + paper.id).then(function (response) {
                    vm.httpSuccessHandler(response, {
                        action: 'delete'
                    });

                    vm.papers.$remove(paper);
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        },

        created () {
            this.paginate();
        }
    }),

    create: Vue.extend({
        template: require('../../template/admin/exam/papers/create.html'),

        data () {
            return {
                form: {}
            };
        },

        methods: {
            create () {
                var vm = this;

                this.$http.post('/api/v1/exam/papers', this.form).then(function (response) {
                    vm.httpSuccessHandler(response, {
                        name: 'exam.papers.index'
                    });
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        }
    }),

    edit: Vue.extend({
        template: require('../../template/admin/exam/papers/edit.html'),

        data () {
            return {
                form: {}
            };
        },

        methods: {
            update () {
                var vm = this;

                this.$http.patch('/api/v1/exam/papers/' + this.$route.params.id, this.form).then(function (response) {
                    vm.httpSuccessHandler(response, {
                        action: 'update',
                        name: 'exam.papers.index'
                    });
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        },

        created () {
            var vm = this;

            this.$http.get('/api/v1/exam/papers/' + this.$route.params.id).then(function (response) {
                vm.$set('form', response.data);
            });
        }
    }),

    questions: {
        index: Vue.extend({
            template: require('../../template/admin/exam/papers/questions/index.html'),

            data() {
                return {
                    paper: {
                        questions: []
                    }
                };
            },

            created() {
                var vm = this;

                this.$http.get('/api/v1/exam/papers/' + this.$route.params.id + '/questions').then(function (response) {
                    vm.$set('paper', response.data);
                });
            }
        }),

        edit: Vue.extend({
            template: require('../../template/admin/exam/papers/questions/edit.html'),

            data () {
                return {
                    form: {
                        questions: []
                    },
                    sets: []
                };
            },

            methods: {
                update() {
                    var vm = this;

                    this.$http.post('/api/v1/exam/papers/' + this.$route.params.id + '/questions', this.form).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            action: 'update',
                            name: 'exam.papers.questions.index',
                            params: {id: vm.$route.params.id}
                        })
                    }, function (response) {
                        vm.httpErrorHandler(response);
                    });
                }
            },

            created() {
                var vm = this;

                this.$http.get('/api/v1/exam/papers/' + this.$route.params.id + '/questions').then(function (response) {
                    var arr = [];

                    for (var i in response.data.questions) {
                        if (response.data.questions.hasOwnProperty(i)) {
                            arr.push('' + response.data.questions[i].id);
                        }
                    }

                    vm.$set('form.questions', arr);
                });

                this.$http.get('/api/v1/exam/sets/all-questions').then(function (response) {
                    vm.$set('sets', response.data);
                });
            }
        })
    }
};
