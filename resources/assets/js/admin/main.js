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

Vue.http.headers.common['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

var routerComponents = {};
var router = new VueRouter();
