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
export default function guest(to, from, next) {
    if (store.getters.check) {
        return next({ name: 'home' });
    }

    return next();
}
