import Vue from 'vue';
import moment from 'moment';

/**
 * Mutate given timestamp to human readable form.
 *
 * @param {String}
 *
 * @return {String}
 */
Vue.prototype.$from = timestamp => {
    return moment(timestamp).fromNow();
};
