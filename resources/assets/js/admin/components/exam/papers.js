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
    })

};
