routerComponents.exam = {
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

        show: Vue.extend({
            template: require('../../template/admin/exam/sets/show.html')
        })
    }
};
