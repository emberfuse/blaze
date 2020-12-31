import '@/Plugins';

import Vue from 'vue';
import { App, plugin } from '@inertiajs/inertia-vue';
import diffForHumans from '@/Plugins/moment';
import initProgressIndicator from '@/Plugins/progress';
import config from '@/Config';

Vue.config.productionTip = false;

Vue.use(plugin);

Vue.mixin({ methods: { route, config, diffForHumans } });

const app = document.getElementById('app');

new Vue({
    metaInfo: {
        titleTemplate: title => title ? `${title} - Preflight` : 'Preflight'
    },

    created() {
        initProgressIndicator();
    },

    render: h => h(App, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => require(`./Views/${name}`).default,
        },
    }),
}).$mount(app);

