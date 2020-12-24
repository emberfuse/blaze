import Vue from "vue";
import moment from "moment";

Vue.prototype.$from = timestamp => {
    return moment(timestamp).fromNow();
};
