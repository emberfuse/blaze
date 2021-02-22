import '@/Plugins';

import { createApp, h } from 'vue';
import {
    App as InertiaApp,
    plugin as InertiaPlugin,
} from '@inertiajs/inertia-vue3';
import config from '@/Config';

Vue.config.productionTip = false;

Vue.mixin({ methods: { route, config } });

const app = document.getElementById('app');

createApp({
    metaInfo: {
        titleTemplate: (title) => (title ? `${title} - Castle` : 'Castle'),
    },

    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route, config } })
    .use(InertiaPlugin)
    .mount(app);
