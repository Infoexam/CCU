"use strict";

/* General Vanilla JS and jQuery */
(function ($) {
    $(function() {
        $(".button-collapse").sideNav();
    });
})(jQuery);

/* Vue.js */
Vue.use(VueResource);
Vue.use(VueRouter);

// add X-XSRF-TOKEN to xhr
Vue.http.headers.common['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

var router = new VueRouter();
var routerComponents = {};
