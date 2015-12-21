routerComponents.exam.lists = {
    index: Vue.extend( {
        template: require('../../template/admin/exam/lists/index.html') ,

        data: function () {
            return {
                lists: [] ,
                pagination: {}
            };
        },

        methods: {
            paginate: function (next) {
                var url;

                if ('undefined' === typeof next) {
                    url = '/api/v1/exam/lists';
                } else {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                this.$http.get(url).then(function (response) {
                    this.$set('lists', response.data.data);
                    delete response.data.data;
                    this.$set('pagination', response.data);
                });
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/exam/lists/' + id).then(function (response) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.lists.splice(index, 1);
                }, function (response) {
                    Materialize.toast($.i18n.t('action.delete.failed'), 3500, 'red');

                });
            }
        },

        ready: function () {
            this.paginate();
        }
    }) ,


    show: Vue.extend({
        template: require('../../template/admin/exam/lists/show.html') , 

        data: function() {
            return {
                lists: {}
            };
        } ,

        ready: function() {
            this.$http.get('/api/v1/exam/lists/' + this.$route.params.id).then(function (response) {
                this.$set('lists' , response.data);
            });
        }
    }),

    edit: Vue.extend({
        template: require('../../template/admin/exam/lists/edit.html'),

        data: function () {
            return {
                form: {},
                categories_apply: [],
                categories_subject: []
            };
        },

        methods: {
            patch: function () {
                this.$http.patch('/api/v1/exam/lists/' + this.$route.params.id, this.form).then(function (response) {
                    this.httpSuccessHandler(response, {name: 'exam.lists.index'})
                }, function (response) {
                    this.httpErrorHandler(response);
                });
            }
        },

        ready: function() {
            this.$http.get('/api/v1/exam/lists/' + this.$route.params.id).then(function (response) {
                this.$set('categories_apply', response.data);

                response.data.apply_id = response.data.apply.id;
                response.data.subject_id = response.data.subject.id;

                this.$set('form', response.data);
            });


            this.$http.get('/api/v1/categories/exam-apply').then(function (response) {
                this.$set('categories_apply', response.data);
            });

            this.$http.get('/api/v1/categories/exam-subject').then(function (response) {
                this.$set('categories_subject', response.data);
            });
        }
    })
};
