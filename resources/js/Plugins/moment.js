import Vue from 'vue';
import moment from 'moment';

/**
 * Mutate given timestamp to human readable form.
 *
 * @param {String}
 *
 * @return {String}
 */
function diffForHumans(timestamp) {
    return moment(timestamp).fromNow();
}

Vue.mixin({ methods: { diffForHumans } });
