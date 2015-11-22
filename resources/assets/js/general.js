"use strict";

/* General Vanilla JS and jQuery */
(function ($) {
    $(function() {
        // 下拉式選單列
        $(".button-collapse").sideNav();

        // http://i18next.com/
        $.i18n.init({
            detectLngQS: 'lang',
            cookieName: 'locale',
            fallbackLng: false,
            lngWhitelist: ['en', 'zh-TW']
        }, function() {
            $('html').i18n();
        });

        // https://github.com/uzairfarooq/arrive
        $(document).arrive('[data-i18n]', function() {
            $(this).i18n();
        });
    });
})(jQuery);

/* Vue.js */
Vue.use(VueResource);
Vue.use(VueRouter);

// add X-XSRF-TOKEN to xhr
Vue.http.headers.common['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

var router = new VueRouter();
var routerComponents = {};
