import Vue from "vue";
import axios from "axios";
import store from "@/Store";
import router from "@/Router";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.withCredentials = true;

axios.interceptors.request.use(request => {
    const token = store.getters.token;

    if (token) {
        request.headers.common.Authorization = `Bearer ${token}`;
    }

    return request;
});

axios.interceptors.response.use(
    response => response,
    error => {
        const { status } = error.response;

        if (status === 401 && store.getters.check) {
            store.commit("logout");

            router.push({ name: "login" });
        }
    }
);

Vue.prototype.$http = axios;
