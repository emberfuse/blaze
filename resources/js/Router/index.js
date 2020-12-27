import Vue from "vue";
import Router from "vue-router";
import routes from "./routes";
import store from "@/Store";
import auth from "./middleware/auth";
import guest from "./middleware/guest";
import { sync } from "vuex-router-sync";

Vue.use(Router);

const router = createRouter();

/**
 * Create new vue router instance.
 *
 * @return  {Router}
 */
function createRouter() {
    const router = new Router(routes);

    router.beforeEach(middlewareHandler);

    return router;
}

/**
 * Handle middleware process.
 *
 * @param   {Route}  to
 * @param   {Route}  from
 * @param   {Function}  next
 *
 * @return  {Any}
 */
async function middlewareHandler(to, from, next) {
    if (!store.getters.check && store.getters.token) {
        try {
            await store.dispatch("fetchUser");
        } catch (error) {}
    }

    if (to.meta.auth) {
        return auth(to, from, next);
    }

    if (to.meta.guest) {
        return guest(to, from, next);
    }

    return next();
}

/**
 * Patch given router navigation methods.
 *
 * @param   {Router}  router
 * @param   {String}  methodName
 *
 * @return  {void}
 */
function patchRouterMethod(router, methodName) {
    router["old" + methodName] = router[methodName];

    router[methodName] = async function(location) {
        return router["old" + methodName](location).catch(error => {
            if (error.name === "NavigationDuplicated") {
                return this.currentRoute;
            }

            throw error;
        });
    };
}

patchRouterMethod(router, "push");
patchRouterMethod(router, "replace");

sync(store, router);

export default router;
