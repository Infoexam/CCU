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
                        this.$http.get(url).then(function (response) {
                            vm.$set('announcements', response.data.data);

                            delete response.data.data;
                            vm.$set('pagination', response.data);
                        });
                    }
                },

                destroy: function (announcement) {
                    var vm = this;

                    this.$http.delete('/api/v1/announcements/' + announcement.id).then(function (response) {
                        vm.httpSuccessHandler(response, {action: 'delete'});
                        vm.announcements.$remove(announcement);
                    });
                }
            },

            created: function () {
                this.paginate();
            }
        }),

        show: Vue.extend({
            template: require('../../template/admin/announcements/show.html'),

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

                    this.$http.post('/api/v1/announcements', this.getFormData(this.form, this.$els.formImage.files, false)).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            name: 'announcements.show',
                            params: {heading: vm.form.heading}
                        });
                    }, function (response) {
                        vm.httpErrorHandler(response);
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

                    this.$http.post('/api/v1/announcements/' + this.form.id, this.getFormData(this.form, this.$els.formImage.files, true)).then(function (response) {
                        vm.httpSuccessHandler(response, {
                            action: 'update',
                            name: 'announcements.show',
                            params: {heading: vm.form.heading}
                        });
                    }, function (response) {
                        vm.httpErrorHandler(response);
                    });
                },

                destroyImage: function (image) {
                    var vm = this;

                    this.$http.delete('/api/v1/images', {
                        uploaded_at: image.uploaded_at,
                        hash: image.hash
                    }).then(function (response) {
                        vm.httpSuccessHandler(response, {action: 'delete'});
                        vm.form.images.$remove(image);
                    });
                }
            },

            ready: function () {
                var vm = this;

                this.$http.get('/api/v1/announcements/' + this.$route.params.heading).then(function (response) {
                    vm.$set('form', response.data);
                    vm.async("$('textarea').trigger('autoresize')");
                }, function (response) {
                    vm.httpErrorHandler(response, {name: 'announcements.index'});
                });
            }
        })
    };
})(Vue, routerComponents);
