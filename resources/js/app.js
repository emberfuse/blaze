import "@/Plugins";

import Vue from "vue";
import Store from "@/Store";
import Router from "@/Router";
import { config } from "@/Config";

Vue.config.productionTip = false;

Vue.mixin({ methods: { route, config } });

const app = new Vue({
    el: "#app",
    store: Store,
    router: Router
});
