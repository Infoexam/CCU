"use strict";

/* General Vanilla JS and jQuery */

(function ($) {
    $(function () {
        // http://materializecss.com/pushpin.html
        $('.feature-bar').pushpin({top: 94});

        // https://i18next.github.io/i18next/
        $.i18n.init({
            detectLngQS: 'lang',
            cookieName: 'locale',
            fallbackLng: false,
            lngWhitelist: ['en', 'zh-TW'],
            resGetPath: '/assets/locales/__ns__-__lng__.json'
        }, function() {
            $('[data-i18n]').i18n();
        });

        /*
         * https://github.com/uzairfarooq/arrive
         */

        $(document).arrive('[data-i18n]', function () {
            $(this).i18n();
        });

        // http://materializecss.com/navbar.html
        $(document).arrive('.button-collapse', function () {
            $(this).sideNav();
        });

        // http://materializecss.com/dialogs.html#tooltip
        $(document).arrive('.tooltipped', function () {
            $(this).tooltip({delay: 50});
        });
        $(document).leave('.tooltipped', function () {
            $(this).tooltip('remove');
        });

        // http://materializecss.com/forms.html#character-counter
        $(document).arrive('input[length], textarea[length]', function () {
            $(this).characterCounter();
        });

        // http://materializecss.com/media.html#materialbox
        $(document).arrive('.materialboxed', function () {
            $(this).materialbox();
        });

        // http://materializecss.com/collapsible.html#intialization
        $(document).arrive('.collapsible', function () {
            $(this).collapsible();
        });

        $(document).arrive('.modal-trigger', function () {
            $(this).leanModal();
        });
    });
})(jQuery);

if ('localhost' === window.location.hostname) {
    Vue.config.debug = true;
}

// add X-XSRF-TOKEN to xhr
// https://laravel.com/docs/5.2/routing#csrf-protection
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());

var router = new VueRouter(),
    routerComponents = {exam: {}},
    routerParentInstance = Vue.extend({template: '<router-view></router-view>'});
