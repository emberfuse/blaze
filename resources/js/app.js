import "@/Plugins";

import Vue from "vue";
import { config } from "@/Config";

Vue.config.productionTip = false;

Vue.mixin({ methods: { route, config } });

const app = new Vue({
    el: "#app"
});
