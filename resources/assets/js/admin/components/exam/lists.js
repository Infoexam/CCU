routerComponents.exam.lists = {
    index: Vue.extend( {
        template: require('../../template/admin/exam/lists/index.html') ,

        data () {
            return {
                lists: [] ,
                pagination: {}
            };
        },

        methods: {
            paginate (next) {
                var url = '/api/v1/exam/lists',
                    vm = this;

                if ('undefined' !== typeof next) {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                this.$http.get(url).then(function (response) {
                    vm.$set('lists', response.data.data);

                    delete response.data.data;
                    vm.$set('pagination', response.data);
                });
            },

            destroy (list) {
                var vm = this;

                this.$http.delete('/api/v1/exam/lists/' + list.code).then(function (response) {
                    vm.httpSuccessHandler(response, {action: 'delete'});
                    vm.lists.$remove(list);
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        },

        created () {
            this.paginate();
        }
    }),


    show: Vue.extend({
        template: require('../../template/admin/exam/lists/show.html') , 

        data () {
            return {
                list: {
                    paper: {},
                    apply: {},
                    subject: {}
                }
            };
        } ,

        ready () {
            var vm = this;

            this.$http.get('/api/v1/exam/lists/' + this.$route.params.code).then(function (response) {
                vm.$set('list', response.data);
            });
        }
    }),

    create: Vue.extend({
        template: require('../../template/admin/exam/lists/create.html'),

        data () {
            "use strict";
            return {
                form: {
                    sets: [],
                    specify_paper: false,
                    allow_apply: false
                },
                data: {}
            };
        },

        computed: {
            isTheory () {
                "use strict";

                var name = '';

                for (var i in this.data['exam.subject']) {
                    if (this.data['exam.subject'].hasOwnProperty(i)) {
                        if (this.form.subject_id == this.data['exam.subject'][i].id) {
                            name = this.data['exam.subject'][i].name;
                        }
                    }
                }

                return name.indexOf('theory') > -1;
            }
        },

        methods: {
            store () {
                "use strict";

                var vm = this;

                this.$http.post('/api/v1/exam/lists', this.form).then(function (response) {
                    vm.httpSuccessHandler(response, {
                        name: 'exam.lists.show',
                        params: {code: response.data.code}
                    });
                }, function (response) {
                    vm.httpErrorHandler(response);
                });
            }
        },

        created () {
            "use strict";

            var vm = this;

            this.$http.get('/api/v1/exam/configs').then(function (response) {
                vm.$set('data.rooms', response.data.allowRoom);
            });

            this.$http.get('/api/v1/categories/exam.apply,exam.subject').then(function (response) {
                vm.$set('data["exam.apply"]', response.data['exam.apply']);
                vm.$set('data["exam.subject"]', response.data['exam.subject']);
            });

            this.$http.get('/api/v1/exam/papers?all=1').then(function (response) {
                vm.$set('data.papers', response.data);
            });

            this.$http.get('/api/v1/exam/sets?all=1').then(function (response) {
                vm.$set('data.sets', response.data);
            });
        }
    }),

    edit: Vue.extend({
        template: require('../../template/admin/exam/lists/edit.html'),

        data () {
            return {
                form: {},
                data: {}
            };
        },

        methods: {
            update () {
                this.$http.patch('/api/v1/exam/lists/' + this.$route.params.code, this.form).then(function (response) {
                    this.httpSuccessHandler(response, {
                        action: 'update',
                        name: 'exam.lists.index',
                        params: {code: response.data.code}
                    })
                }, function (response) {
                    this.httpErrorHandler(response);
                });
            }
        },

        created () {
            var vm = this;

            this.$http.get('/api/v1/exam/lists/' + this.$route.params.code + '?edit=1').then(function (response) {
                response.data.began_at = response.data.began_at.replace(' ', 'T');

                vm.$set('form', response.data);
            });

            this.$http.get('/api/v1/exam/configs').then(function (response) {
                vm.$set('data.rooms', response.data.allowRoom);
            });
        }
    })
};
