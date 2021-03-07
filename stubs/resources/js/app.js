import axios from 'axios';
import { createApp, h } from 'vue';
import {
    App as InertiaApp,
    plugin as InertiaPlugin,
} from '@inertiajs/inertia-vue3';

import Config from '@cratespace/config-js';
import diffForHumans from './Plugins/moment';
import { InertiaProgress } from '@inertiajs/progress';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = document.getElementById('app');

createApp({
    metaInfo: {
        titleTemplate: (title) =>
            title ? `${title} - Preflight` : 'Preflight',
    },

    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: (name) => require(`./Views/${name}`).default,
        }),
})
    .mixin({ methods: { route, diffForHumans } })
    .use(InertiaPlugin)
    .use(Config, require('./Config/items.json'))
    .mount(app);

app.config.globalProperties.$http = axios;

InertiaProgress.init({
    delay: 250,
    color: '#3B82F6',
    includeCSS: true,
    showSpinner: false,
});
