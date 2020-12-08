import Vue from 'vue';
import VueNotify from 'vue-notifyjs';
import VTooltip from 'v-tooltip';
import _ from 'lodash';
import moment from 'moment';

import router from '~/router';
import env from '~/env';
import notification from '~/services/notification';
import lang from 'element-ui/lib/locale/lang/en';
import locale from 'element-ui/lib/locale';
import App from './App.vue';
import {store} from './store/store';

require('./bootstrap');

Vue.use(VueNotify);
Vue.use(VTooltip);
/*Vue.use(VeeValidate);*/
locale.use(lang);

Vue.prototype.$env = env;
Vue.prototype.$notification = notification;

Vue.prototype.$_ = _;
Vue.prototype.$moment = moment;
// Custom Plugins / Services
import localStorage from '~/services/localStorage';

Vue.prototype.$localStorage = localStorage;

import apiService from '~/services/api';

Vue.prototype.$apiService = apiService;

import authService from '~/services/auth';

Vue.prototype.$auth = authService;

import GlobalDirectives from './globalDirectives';

Vue.use(GlobalDirectives);

import GlobalComponents from './gloablComponents';

Vue.use(GlobalComponents);

import sidebarLinks from './sidebarLinks';
import SideBar from './components/UIComponents/SidebarPlugin';

Vue.use(SideBar, {sidebarLinks: sidebarLinks});

window.$ = window.jQuery = require('jquery');

const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    render: h => h(App)
});
window.app = app;
