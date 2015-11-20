"use strict";

/* Vanilla JS and jQuery */
(function ($) {
    $(function() {
        $(".button-collapse").sideNav();

        // 判斷是否帶有 signIn 參數，如有則頁面載入後即顯示登入框
        if (-1 !== location.search.indexOf('signIn=1')) {
            $('#sign-in-modal').openModal();
        }

        $.i18n.init({
            detectLngQS: 'lang',
            cookieName: 'locale',
            fallbackLng: false,
            lngWhitelist: ['en', 'zh-TW']
        }, function() {
            $('html').i18n();
        });
    });
})(jQuery);

/* Vue.js */
Vue.use(VueResource);
Vue.use(VueRouter);

Vue.http.headers.common['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

var routerComponents = {};
var router = new VueRouter();
