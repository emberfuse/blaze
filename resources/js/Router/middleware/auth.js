import Vue from 'vue';
import store from '@/Store';

/**
 * Handle route guard.
 *
 * @param   {Route}  to
 * @param   {Route}  from
 * @param   {Function}  next
 *
 * @return  {Function}
 */
export default function auth(to, from, next) {
    if (!store.getters.check) {
        Vue.$cookies.set('intended_url', to.path);

        return next({ name: 'login' });
    }

    return next();
}
