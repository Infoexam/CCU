routerComponents.exam.lists = {
    index: Vue.extend( {
        template: require('../../template/admin/exam/lists/index.html') ,

        data: function () {
            return {sets: [], pagination: {}};
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
                        this.$set('sets', data.data);
                        delete data.data;
                        this.$set('pagination', data);
                    });
            },
        },

        ready: function () {
            this.paginate();
        }
    }) ,


    show: Vue.extend({
        template: require('../../template/admin/exam/lists/show.html') , 

        data: function() {
            return { sets: {} };
        } , 
        ready: function() {
            var url = '/api/v1/exam/lists/' + this.$route.params.id;
            this.$http.get(url , function (data , status , request){
                    this.$set('sets' , data);
            });
        }




    })
};
