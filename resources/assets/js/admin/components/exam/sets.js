(function (Vue, routerComponents) {
    var mixin = {
        created: function () {
            var vm = this;

            this.$http.get('/api/v1/categories/exam-category').then(function (response) {
                vm.$set('categories', response.data);
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
                        this.$http.get(url).then(function (response) {
                            vm.$set('sets', response.data.data);

                            delete response.data.data;
                            vm.$set('pagination', response.data);
                        });
                    }
                },

                destroy: function (sets) {
                    if (confirm('確定要刪除？')) {
                        var vm = this;

                        this.$http.delete('/api/v1/exam/sets/' + sets.id).then(function (response) {
                            vm.httpSuccessHandler(response, {action: 'delete'});
                            vm.sets.$remove(sets);
                        }, function (response) {
                            vm.httpErrorHandler(response);
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

                    this.$http.post('/api/v1/exam/sets', this.form).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            name: 'exam.sets.index'
                        });
                    }, function (response) {
                        vm.httpErrorHandler(response);
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

                    this.$http.patch('/api/v1/exam/sets/' + this.$route.params.id, this.form).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            action: 'update',
                            name: 'exam.sets.show',
                            params: {id: vm.$route.params.id}
                        });
                    }, function (response) {
                        vm.httpErrorHandler(response);
                    });
                }
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/exam/sets/' + this.$route.params.id).then(function (response) {
                    response.data.category_id = response.data.category.id;
                    vm.$set('form', response.data);
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

                        this.$http.delete('/api/v1/exam/sets/' + this.$route.params.id + '/questions/' + question.id).then(function (response) {
                            vm.httpSuccessHandler(response, {action: 'delete'});
                            vm.sets.questions.$remove(question);
                        });
                    }
                },

                created: function() {
                    var vm = this;

                    this.$http.get('/api/v1/exam/sets/' + this.$route.params.id + '/questions').then(function (response) {
                        vm.$set('sets', response.data);
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

                    this.$http.get('/api/v1/exam/sets/' + this.$route.params.id + '/questions/' + this.$route.params.question).then(function (response) {
                        var answers = [];

                        for (var i in response.data.options) {
                            if (response.data.options.hasOwnProperty(i)) {
                                if (response.data.answers.indexOf(response.data.options[i].id)) {
                                    answers.push(parseInt(i) + 1);
                                }
                            }
                        }

                        response.data.answers = answers;

                        vm.$set('question', response.data);
                    }, function (response) {
                        vm.httpErrorHandler(response, {
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
                        question:{
                            content: [],
                            image: []
                        },
                        option :[
                            {content : [] , image:[]}
                        ],
                        explanation: {},
                        difficulty_id:{},
                        multiple:{},
                        answer:[],
                        c: 1
                    }
                },

                mixins: [mixin],

                methods: {
                    store: function () {
                        var vm = this;

                        this.$http.post('/api/v1/exam/sets/' + this.$route.params.id + '/questions', this.form).then(function (response) {
                            alert("test");
                            vm.httpSuccessHandler(response, {
                                name: 'exam.sets.index'
                            });
                        }, function (response) {
                            vm.httpErrorHandler(response);
                        });
                    }
                }


            }),

            edit: Vue.extend({
                template: require('../../template/admin/exam/sets/questions/edit.html'),

                data: function () {
                    return {
                        form: {
                        }
                    }
                }
            })
        }
    };
})(Vue, routerComponents);
