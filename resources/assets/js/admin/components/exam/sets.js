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
                var url;

                if ('undefined' === typeof next) {
                    url = '/api/v1/exam/sets';
                } else {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                if ('string' === typeof  url) {
                    this.$http.get(url, function (data, status, request) {
                        this.$set('sets', data.data);

                        delete data.data;
                        this.$set('pagination', data);
                    });
                }
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/exam/sets/' + id, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.sets.splice(index, 1);
                }).error(function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.failed'), 3500, 'red');
                });
            }
        },

        ready: function () {
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

        methods: {
            store: function () {
                this.$http.post('/api/v1/exam/sets', this.form, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.create.success'), 3500, 'green');

                    router.go({name: 'exam.sets.index'});
                }).error(function (data, status, request) {
                    if (422 === status) {
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                Materialize.toast(data[key], 3500, 'red');
                            }
                        }
                    } else {
                        Materialize.toast($.i18n.t('action.create.failed'), 3500, 'red');
                    }
                });
            }
        },

        ready: function() {
            this.$http.get('/api/v1/categories/exam-category', function (data, status, request) {
                this.$set('categories', data);
            });
        }
    }),

    show: Vue.extend({
        template: require('../../template/admin/exam/sets/show.html'),

        data: function() {
            return {
                sets: {}
            };
        },

        ready: function() {
            this.$http.get('/api/v1/exam/sets/' + this.$route.params.id, function (data, status, request) {
                this.$set('sets', data);
            });
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

        methods: {
            patch: function () {
                this.$http.patch('/api/v1/exam/sets/' + this.$route.params.id, this.form, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.update.success'), 3500, 'green');

                    router.go({name: 'exam.sets.index'});
                }).error(function (data, status, request) {
                    if (422 === status) {
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                Materialize.toast(data[key], 3500, 'red');
                            }
                        }
                    } else {
                        Materialize.toast($.i18n.t('action.update.failed'), 3500, 'red');
                    }
                });
            }
        },

        ready: function() {
            this.$http.get('/api/v1/exam/sets/' + this.$route.params.id, function (data, status, request) {
                data.category_id = data.category.id;

                this.$set('form', data);
            });

            this.$http.get('/api/v1/categories/exam-category', function (data, status, request) {
                this.$set('categories', data);
            });
        }
    })
};
