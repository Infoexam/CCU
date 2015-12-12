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




    })
};
