(function (Vue, $, Materialize){
    Vue.mixin({
        methods: {
            httpSuccessHandler (response, options) {
                switch (response.status) {
                    case 200:
                        switch (options.action) {
                            case 'update':
                                this.toastSuccess($.i18n.t('action.update.success'));

                                if (options.hasOwnProperty('name')) {
                                    this.routerGo(options);
                                }

                                return;
                            case 'delete':
                                this.toast($.i18n.t('action.delete.success'), 'orange');
                                return;
                        }
                        return;
                    case 201: this.created(options); return;
                }
            },

            created (options) {
                this.toastSuccess($.i18n.t('action.create.success'));

                if (options && options.hasOwnProperty('name')) {
                    this.routerGo(options);
                }
            },

            httpErrorHandler (response, options) {
                switch (response.status) {
                    case 404: this.notFound(options); return;
                    case 422: this.unprocessableEntity(response.data); return;
                }
            },

            notFound (options) {
                options.name = options.name || 'home';

                this.toastError('Page Not Found!').routerGo(options);
            },

            unprocessableEntity (data) {
                for (var key in data.messages) {
                    if (data.messages.hasOwnProperty(key)) {
                        this.toastError(data.messages[key]);
                    }
                }
            },

            toastSuccess (message) {
                return this.toast(message, 'green');
            },

            toastError (message) {
                return this.toast(message, 'red');
            },

            toast (message, style, duration) {
                Materialize.toast(message, duration || 3500, style);

                return this;
            },

            routerGo (options) {
                router.go({name: options.name, params: options.params || {}});
            },

            async (expression) {
                setTimeout(function () {
                    eval(expression);
                }, 0);
            },

            clone (target) {
                return JSON.parse(JSON.stringify(target));
            },

            empty (target) {
                switch (typeof target) {
                    case 'object':
                        return (Object === target.constructor)
                            ? (0 === Object.keys(target).length)
                            : (0 === target.length);
                    case 'string':
                        return 0 === target.length;
                    case 'number':
                        return 0 === target;
                    default:
                        return false;
                }
            },

            buttonLink (target) {
                document.getElementById(target).click();
            }
        }
    });
})(Vue, jQuery, Materialize);
