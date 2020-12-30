import './Plugins';

import Vue from 'vue';
import { App, plugin } from '@inertiajs/inertia-vue';
import config from './Config';

Vue.config.productionTip = false;

Vue.use(plugin);

Vue.mixin({ methods: { route, config } });

const app = document.getElementById('app');

new Vue({
    render: h => h(App, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => require(`./Views/${name}`).default,
        },
    }),
}).$mount(app);

