import Vue from 'vue'
import VueResource from 'vue-resource'
import { router, infoexam } from './routes/index'

let standardsTree = 'localhost' === window.location.hostname ? `x` : `vnd`;

Vue.use(VueResource);

Vue.http.options.root = `/api`;
Vue.http.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(('; ' + document.cookie).split('; XSRF-TOKEN=').pop().split(';').shift());
Vue.http.headers.common['Accept'] = `Accept: application/${standardsTree}.infoexam.v1+json`;

router.start(infoexam, 'main')

require(`../sass/app.scss`)
