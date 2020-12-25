import Cookies from "vue-cookies";
import store from "@/Store";

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
        Cookies.set("intended_url", to.path);

        return next({ name: "login" });
    }

    return next({ name: "home" });
}
