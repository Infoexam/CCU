routerComponents.announcements = {
    index: Vue.extend({
        template: require('../../template/admin/announcements/index.html'),

        data: function () {
            return {announcements: [], pagination: {}};
        },

        methods: {
            paginate: function (next) {
                var url;

                if ('undefined' === typeof next) {
                    url = '/api/v1/announcements';
                } else {
                    url = this.pagination[next ? 'next_page_url' : 'prev_page_url'];
                }

                if ('string' === typeof url) {
                    this.$http.get(url, function (data, status, request) {
                        this.announcements = data.data;

                        delete data.data;
                        this.pagination = data;
                    });
                }
            },

            destroy: function (id, index) {
                this.$http.delete('/api/v1/announcements/' + id, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.announcements.splice(index, 1);
                }).error(function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.failed'), 3500, 'red');
                });
            }
        },

        ready: function () {
            this.paginate();
        }
    }),

    create: Vue.extend({
        template: require('../../template/admin/announcements/create.html'),

        data: function () {
            return {form: {}};
        },

        methods: {
            store: function () {
                var data = new FormData(),
                    files = this.$els.formImage.files;

                for(var key in files){
                    if (files.hasOwnProperty(key)) {
                        data.append('image['+key+']', files[key]);
                    }
                }

                data.append('heading', this.form.heading);
                data.append('link', this.form.link || '');
                data.append('content', this.form.content);

                this.$http.post('/api/v1/announcements', data, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.create.success'), 3500, 'green');

                    router.go({name: 'announcements.show', params: {heading: this.form.heading}});
                }).error(function (data, status, request) {
                    if (422 === status) {
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                Materialize.toast(data[key], 3500, 'red');
                            }
                        }
                    } else {
                        Materialize.toast($.i18n.t('action.create.failed'), 3500, 'red');
                    }
                });
            }
        }
    }),

    show: Vue.extend({
        template: require('../../template/admin/announcements/show.html'),

        data: function () {
            return {announcement: {}};
        },

        ready: function () {
            this.$http.get('/api/v1/announcements/' + this.$route.params.heading, function (data, status, request) {
                this.announcement = data;
            }).error(function (data, status, request) {
                if (404 === status) {
                    Materialize.toast('404 Not Found', 3500, 'red');

                    router.go({name: 'announcements.index'});
                }
            });
        }
    }),

    edit: Vue.extend({
        template: require('../../template/admin/announcements/edit.html'),

        data: function () {
            return {form: {}};
        },

        methods: {
            update: function () {
                var data = new FormData(),
                    files = this.$els.formImage.files;

                for(var key in files){
                    if (files.hasOwnProperty(key)) {
                        data.append('image['+key+']', files[key]);
                    }
                }

                data.append('heading', this.form.heading);
                data.append('link', this.form.link || '');
                data.append('content', this.form.content);

                this.$http.patch('/api/v1/announcements', data, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.update.success'), 3500, 'green');

                    router.go({name: 'announcements.show', params: {heading: this.form.heading}});
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
            },

            destroyImage: function (index) {
                this.$http.delete('/api/v1/images', {
                    uploaded_at: this.form.images[index].uploaded_at,
                    hash: this.form.images[index].hash
                }, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    this.form.images.splice(index, 1);
                });
            }
        },

        ready: function () {
            this.$http.get('/api/v1/announcements/' + this.$route.params.heading, function (data, status, request) {
                this.form = data;

                setTimeout(function() {
                    $('input').focus();
                    $('textarea').focus().trigger('autoresize');
                }, 50);
            }).error(function (data, status, request) {
                if (404 === status) {
                    Materialize.toast('404 Not Found', 3500, 'red');

                    route.go({name: 'announcements.index'});
                }
            });
        }
    })
};
