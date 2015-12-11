(function (Vue, routerComponents) {
    var mixin = {
        methods: {
            getFormData: function (form, files, patch) {
                var result = new FormData;

                for (var key in files) {
                    if (files.hasOwnProperty(key)) {
                        result.append('image[]', files[key]);
                    }
                }

                result.append('heading', form.heading);
                result.append('link', form.link || '');
                result.append('content', form.content);

                if (true === patch) {
                    result.append('_method', 'PATCH');
                }

                return result;
            }
        }
    };

    routerComponents.announcements = {
        index: Vue.extend({
            template: require('../../template/admin/announcements/index.html'),

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
                        this.$http.get(url, function (data, status, request) {
                            vm.announcements = data.data;

                            delete data.data;
                            vm.pagination = data;
                        });
                    }
                },

                destroyAnnouncement: function (announcement) {
                    var vm = this;

                    this.$http.delete('/api/v1/announcements/' + announcement.id, function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {action: 'delete'});
                        vm.announcements.$remove(announcement);
                    });
                }
            },

            ready: function () {
                this.paginate();
            }
        }),

        show: Vue.extend({
            template: require('../../template/admin/announcements/show.html'),

            data: function () {
                return {
                    announcement: {}
                };
            },

            ready: function () {
                var vm = this;

                this.$http.get('/api/v1/announcements/' + this.$route.params.heading, function (data, status, request) {
                    vm.$set('announcement', data);
                }).error(function (data, status, request) {
                    vm.httpErrorHandler(data, status, {name: 'announcements.index'});
                });
            }
        }),

        create: Vue.extend({
            template: require('../../template/admin/announcements/create.html'),

            data: function () {
                return {
                    form: {}
                };
            },

            mixins: [mixin],

            methods: {
                store: function () {
                    var vm = this;

                    this.$http.post('/api/v1/announcements', this.getFormData(this.form, this.$els.formImage.files, false), function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {
                            name: 'announcements.show',
                            params: {heading: vm.form.heading}
                        });
                    }).error(function (data, status, request) {
                        vm.httpErrorHandler(data, status);
                    });
                }
            }
        }),

        edit: Vue.extend({
            template: require('../../template/admin/announcements/edit.html'),

            data: function () {
                return {
                    form: {}
                };
            },

            mixins: [mixin],

            methods: {
                update: function () {
                    var vm = this;

                    this.$http.post('/api/v1/announcements/' + this.form.id, this.getFormData(this.form, this.$els.formImage.files, true), function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {
                            action: 'update',
                            name: 'announcements.show',
                            params: {heading: vm.form.heading}
                        });
                    }).error(function (data, status, request) {
                        vm.httpErrorHandler(data, status);
                    });
                },

                destroyImage: function (image) {
                    var vm = this;

                    this.$http.delete('/api/v1/images', {
                        uploaded_at: image.uploaded_at,
                        hash: image.hash
                    }, function (data, status, request) {
                        vm.httpSuccessHandler(data, status, {action: 'delete'});
                        vm.form.images.$remove(image);
                    });
                }
            },

            ready: function () {
                var vm = this;

                this.$http.get('/api/v1/announcements/' + this.$route.params.heading, function (data, status, request) {
                    vm.$set('form', data);
                    async("$('textarea').trigger('autoresize')");
                }).error(function (data, status, request) {
                    vm.httpErrorHandler(data, status, {name: 'announcements.index'});
                });
            }
        })
    };
})(Vue, routerComponents);
