routerComponents.exam.papers = {
    index: Vue.extend({
        template: require('../../template/admin/exam/papers/index.html'),

        data: function () {
            return {papers: [], pagination: {}};
        },

        methods: {
            paginate: function (next) {
                var url;

                if ('undefined' === typeof next) {
                    url = '/api/v1/exam/papers';
                } else {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                if ('string' === typeof url) {
                    this.$http.get(url).then(function (response) {
                        this.$set('papers', response.data.data);
                        delete response.data.data;
                        this.$set('pagination', response.data);
                    });
                }
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/exam/papers/' + id).then(function (response) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.papers.splice(index, 1);
                }, function (response) {
                    Materialize.toast($.i18n.t('action.delete.failed'), 3500, 'red');
                });
            }
        },

        ready: function () {
            this.paginate();
        }
    }),

    show: Vue.extend({
        template: require('../../template/admin/exam/papers/show.html'),

        data: function() {
           return {
               papers: {}
           };
        },

        ready: function() {
            this.$http.get('/api/v1/exam/papers/' + this.$route.params.id).then(function (response) {
                this.$set('papers', response.data);
            });
        }
    }),

    edit: Vue.extend({
        template: require('../../template/admin/exam/papers/edit.html'),

        data: function () {
            return {
                form: {},
                categories: []
            };
        },

        methods: {
            patch: function () {
                this.$http.patch('/api/v1/exam/papers/' + this.$route.params.id, this.form).then(function (response) {
                    this.httpSuccessHandler(response, {name: 'exam.papers.index'});
                }, function (response) {
                    this.httpErrorHandler(response);
                });
            }
        },

        ready: function() {
            this.$http.get('/api/v1/exam/papers/' + this.$route.params.id).then(function (response) {
                this.$set('form', response.data);
            });

            this.$http.get('/api/v1/categories/exam-category').then(function (response) {
                this.$set('categories', response.data);
            });
        }
    })
};
