"use strict";

/* Vanilla JS and jQuery */
(function ($) {
    $(function() {
        $(".button-collapse").sideNav();
    });
})(jQuery);

/* Vue.js */
Vue.use(VueResource);
Vue.use(VueRouter);

var routerComponents = {};
