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
            lngWhitelist: ['en', 'zh-TW'],
            resGetPath: '/locales/__ns__-__lng__.json'
        }, function() {
            $('[data-i18n]').i18n();
        });

        // https://github.com/uzairfarooq/arrive
        $(document).arrive('[data-i18n]', function() {
            $(this).i18n();
        });

        $(document).arrive('.tooltipped', function() {
            $(this).tooltip({delay: 50});
        });

        $(document).arrive('input[length], textarea[length]', function() {
            $(this).characterCounter();
        });

        $(document).arrive('select', function() {
            $(this).material_select();
        });

        $(document).arrive('.materialboxed', function() {
            $(this).materialbox();
        });
    });
})(jQuery);

// add X-XSRF-TOKEN to xhr
Vue.http.headers.common['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

var router = new VueRouter();
var routerComponents = {};
