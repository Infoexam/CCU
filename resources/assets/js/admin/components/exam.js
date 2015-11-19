routerComponents.exam = {
    index: Vue.extend({template: '<router-view></router-view>'}),
    sets: {
        index: Vue.extend({
            template: require('../../template/admin/exam/sets/index.html'),

            data: function () {
                return {sets: [], pagination: {}};
            },

            methods: {
                paginate: function (next) {
                    var url;

                    if ('undefined' === typeof next) {
                        url = '/api/v1/exam/sets';
                    } else {
                        url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                    }

                    if ('string' === typeof url) {
                        this.$http.get(url, function (data, status, request) {
                            this.$set('sets', data.data);

                            delete data.data;
                            this.$set('pagination', data);
                        });
                    }

                }
            },

            ready: function () {
                this.paginate();
            }
        }),

        show: Vue.extend({
            template: require('../../template/admin/exam/sets/show.html')
        })
    }
};
