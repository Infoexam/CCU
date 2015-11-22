routerComponents.ipRules = {
    index: Vue.extend({
        template: require('../../template/admin/ip-rules/index.html'),

        data: function () {
            return {ipRules: {}, form: {ip: '', rules: {}}};
        },

        methods: {
            edit: function (key) {
                this.form = {
                    ip: key,
                    rules: {
                        student: this.ipRules[key].student,
                        exam: this.ipRules[key].exam,
                        admin: this.ipRules[key].admin
                    }
                };

                $('#ip').focus();
            },

            update: function () {
                this.$http.post('/api/v1/ip-rules', {
                    ip: this.form.ip,
                    rules: this.form.rules
                }, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.update.success'), 3500, 'green');

                    Vue.set(this.ipRules, this.form.ip, this.form.rules);

                    this.form.ip = '';
                    this.form.rules = {student: 1};
                });
            },

            destroy: function (key) {
                this.$http.delete('/api/v1/ip-rules', {ip: key}, function (data, status, request) {
                    Materialize.toast($.i18n.t('action.delete.success'), 3500, 'green');

                    Vue.delete(this.ipRules, key);
                });
            },

            isEmptyObject: function (o) {
                return 0 === Object.keys(o).length;
            }
        },

        ready: function () {
            this.$http.get('/api/v1/ip-rules', function (data, status, request) {
                this.ipRules = Object.keys(data).length ? data : {};
            });
        }
    })
};
