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
                    this.$http.get(url, function (data, status, request) {
                        this.$set('papers', data.data);

                        delete data.data;
                        this.$set('pagination', data);
                    });
                }
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/exam/papers/' + id, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.papers.splice(index, 1);
                }).error(function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.failed'), 3500, 'red');
                });
            }
        },

        ready: function () {
            this.paginate();
        }
    }),

    show: Vue.extend({
        template: require('../../template/admin/exam/papers/show.html') , 
        data: function() {
           return {papers: {}};     
        } ,
        ready: function() {
            var url = '/api/v1/exam/papers/' + this.$route.params.id;
            this.$http.get(url , function (data , status , request){
                    this.$set('papers' , data);
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
                
                this.$http.patch('/api/v1/exam/papers/' + this.$route.params.id, this.form, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.update.success'), 3500, 'green');

                    router.go({name: 'exam.papers.index'});
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
            this.$http.get('/api/v1/exam/papers/' + this.$route.params.id, function (data, status, request) {
                

                this.$set('form', data);
            });

            this.$http.get('/api/v1/categories/exam-category', function (data, status, request) {
                this.$set('categories', data);
            });
        }
    })

};
