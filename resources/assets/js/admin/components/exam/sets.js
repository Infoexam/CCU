(function (Vue, routerComponents) {
    var mixin = {
        created: function () {
            var vm = this;

            this.$http.get('/api/v1/categories/exam-category', function (data, status, request) {
                vm.$set('categories', data);
            });
        }
    };

    routerComponents.exam.sets = {
        index: Vue.extend({
            template: require('../../template/admin/exam/sets/index.html'),

            data: function () {
                return {
                    sets: [],
                    pagination: {}
                };
            },

            methods: {
                paginate: function (next) {
                    var url = '/api/v1/exam/sets',
                        vm = this;

                    if ('undefined' !== typeof next) {
                        url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                    }

                    if ('string' === typeof  url) {
                        this.$http.get(url, function (data, status, request) {
                            vm.$set('sets', data.data);

                            delete data.data;
                            vm.$set('pagination', data);
                        });
                    }
                },

                destroy: function (sets) {
                    if (confirm('確定要刪除？')) {
                        var vm = this;

                        this.$http.delete('/api/v1/exam/sets/' + sets.id, function (data, status, request) {
                            vm.httpSuccessHandler(data, status, {action: 'delete'});
                            vm.sets.$remove(sets);
                        }).error(function (data, status, request) {
                            vm.httpErrorHandler(data, status);
                        });
                    }
                }
            },

            created: function () {
                this.paginate();
            }
        }),

        create: Vue.extend({
            template: require('../../template/admin/exam/sets/create.html'),

            data: function () {
                return {
                    form: {},
                    categories: []
                };
            },

            mixins: [mixin],

            methods: {
                store: function () {
                    var vm = this;

                    this.$http.post('/api/v1/exam/sets', this.form, function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {
                            name: 'exam.sets.index'
                        });
                    }).error(function (data, status, request) {
                        vm.httpErrorHandler(data, status);
                    });
                }
            }
        }),

        edit: Vue.extend({
            template: require('../../template/admin/exam/sets/edit.html'),

            data: function () {
                return {
                    form: {},
                    categories: []
                };
            },

            mixins: [mixin],

            methods: {
                patch: function () {
                    var vm = this;

                    this.$http.patch('/api/v1/exam/sets/' + this.$route.params.id, this.form, function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {
                            action: 'update',
                            name: 'exam.sets.show',
                            params: {id: vm.$route.params.id}
                        });
                    }).error(function (data, status, request) {
                        vm.httpErrorHandler(data, status);
                    });
                }
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/exam/sets/' + this.$route.params.id, function (data, status, request) {
                    data.category_id = data.category.id;
                    vm.$set('form', data);
                });
            }
        }),

        questions: {
            index: Vue.extend({
                template: require('../../template/admin/exam/sets/questions/index.html'),

                data: function() {
                    return {
                        sets: {
                            questions: []
                        }
                    };
                },

                methods: {
                    destroy: function (question) {
                        var vm = this;

                        this.$http.delete('/api/v1/exam/sets/' + this.$route.params.id + '/questions/' + question.id, function (data, status, request) {
                            vm.httpSuccessHandler(data, status, {action: 'delete'});
                            vm.sets.questions.$remove(question);
                        });
                    }
                },

                created: function() {
                    var vm = this;

                    this.$http.get('/api/v1/exam/sets/' + this.$route.params.id + '/questions', function (data, status, request) {
                        vm.$set('sets', data);
                    });
                }
            }),

            show: Vue.extend({
                template: require('../../template/admin/exam/sets/questions/show.html'),

                data: function() {
                    return {
                        question: {
                            difficulty: {},
                            explanation: null,
                            answers: []
                        }
                    };
                },

                created: function() {
                    var vm = this;

                    this.$http.get('/api/v1/exam/sets/' + this.$route.params.id + '/questions/' + this.$route.params.question, function (data, status, request) {
                        var answers = [];

                        for (var i in data.options) {
                            if (data.options.hasOwnProperty(i)) {
                                if (data.answers.indexOf(data.options[i].id)) {
                                    answers.push(parseInt(i) + 1);
                                }
                            }
                        }

                        data.answers = answers;

                        vm.$set('question', data);
                    }).error(function (data, status, request) {
                        vm.httpErrorHandler(data, status, {
                            name: 'exam.sets.questions.index',
                            params: {
                                id: vm.$route.params.id
                            }
                        });
                    });
                }
            }),

            create: Vue.extend({
                template: require('../../template/admin/exam/sets/questions/create.html'),

                data: function () {
                    return {
                        form: {}
                    }
                }
            }),

            edit: Vue.extend({
                template: require('../../template/admin/exam/sets/questions/edit.html'),

                data: function () {
                    return {
                        form: {}
                    }
                }
            })
        }
    };
})(Vue, routerComponents);
