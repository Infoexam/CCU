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
                    this.$http.get(url, function (data, status, request) {
                        this.$set('lists', data.data);
                        delete data.data;
                        this.$set('pagination', data);
                    });
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/exam/lists/' + id, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.lists.splice(index, 1);
                }).error(function (data, status, request) {
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
            return { lists: {} };
        } , 
        ready: function() {
            var url = '/api/v1/exam/lists/' + this.$route.params.id;
            this.$http.get(url , function (data , status , request){
                    this.$set('lists' , data);
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

                this.$http.patch('/api/v1/exam/lists/' + this.$route.params.id, this.form, function (data, status, request) {
                    
                    Materialize.toast($.i18n.t('action.update.success'), 3500, 'green');

                    router.go({name: 'exam.lists.index'});
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
            this.$http.get('/api/v1/exam/lists/' + this.$route.params.id, function (data, status, request) {
                data.apply_id = data.apply.id;
                data.subject_id = data.subject.id;

                this.$set('form', data);
            });

            this.$http.get('/api/v1/categories/exam-apply', function (data, status, request) {
                this.$set('categories_apply', data);
            });

            this.$http.get('/api/v1/categories/exam-subject' , function (data , status , request) {
                this.$set('categories_subject' , data);
            });
        }
    })



};
