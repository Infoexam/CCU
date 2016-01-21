"use strict";

/* General Vanilla JS and jQuery */
(function ($) {
    $(function() {
        // 下拉式選單列
        $('.button-collapse').sideNav();

        // http://materializecss.com/pushpin.html
        $('.feature-bar').pushpin({offset: 94});

        // http://i18next.com/
        $.i18n.init({
            detectLngQS: 'lang',
            cookieName: 'locale',
            fallbackLng: false,
            lngWhitelist: ['en', 'zh-TW'],
            resGetPath: '/assets/locales/__ns__-__lng__.json'
        }, function() {
            $('[data-i18n]').i18n();
        });

        // https://github.com/uzairfarooq/arrive

        $(document).arrive('[data-i18n]', function() {
            $(this).i18n();
        });

        // http://materializecss.com/dialogs.html#tooltip
        $(document).arrive('.tooltipped', function() {
            $(this).tooltip({delay: 50});
        });
        $(document).leave('.tooltipped', function() {
            $(this).tooltip('remove');
        });

        // http://materializecss.com/forms.html#character-counter
        $(document).arrive('input[length], textarea[length]', function() {
            $(this).characterCounter();
        });

        // http://materializecss.com/forms.html#select
        $(document).arrive('select', function() {
            $(this).material_select();
        });

        // http://materializecss.com/media.html#materialbox
        $(document).arrive('.materialboxed', function() {
            $(this).materialbox();
        });

        // 行動版點擊選單列連結時，需觸發移除覆蓋事件
        $(document).on('click', '#nav-mobile-menu a[href]', function () {
            var overlay = document.getElementById('sidenav-overlay');

            if (null !== overlay) {
                overlay.click();

                $('#nav-mobile-menu a.active:not([href])').click();
            }
        });
    });
})(jQuery);

// add X-XSRF-TOKEN to xhr
// http://laravel.com/docs/5.1/routing#csrf-protection
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());

var router = new VueRouter(),
    routerComponents = {};

if (-1 !== window.location.pathname.indexOf('/admin')) {
    routerComponents.exam = {};
}
