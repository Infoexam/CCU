(function (Vue, routerComponents) {
    routerComponents.announcements = {
        index: Vue.extend({
            template: require('../../template/student/announcements/index.html'),

            data: function () {
                return {
                    announcements: [],
                    pagination: {}
                };
            },

            methods: {
                paginate: function (next) {
                    var url = '/api/v1/announcements',
                        vm = this;

                    if ('undefined' !== typeof next) {
                        url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                    }

                    if ('string' === typeof url) {
                        this.$http.get(url).then(function (response) {
                            vm.$set('announcements', response.data.data);

                            delete response.data.data;
                            vm.$set('pagination', response.data);
                        });
                    }
                }
            },

            created: function () {
                this.paginate();
            }
        }),

        show: Vue.extend({
            template: require('../../template/student/announcements/show.html'),

            data: function () {
                return {
                    announcement: {
                        created_at: {},
                        updated_at: {}
                    }
                };
            },

            created: function () {
                var vm = this;

                this.$http.get('/api/v1/announcements/' + this.$route.params.heading).then(function (response) {
                    vm.$set('announcement', response.data);
                }, function (response) {
                    vm.httpErrorHandler(response, {name: 'announcements.index'});
                });
            }
        })
    };
})(Vue, routerComponents);
