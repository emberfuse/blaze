import '@/Plugins';

import Vue from 'vue';
import store from '@/Store';
import router from '@/Router';
import config from '@/Config';

Vue.config.productionTip = false;

Vue.mixin({ methods: { route, config } });

const app = new Vue({
    store,
    router,
    el: '#app',
});
