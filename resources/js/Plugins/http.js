import Vue from 'vue';
import http from 'axios';
import store from '@/Store';
import router from '@/Router';

http.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
http.defaults.withCredentials = true;

http.interceptors.request.use((request) => {
    const token = store.getters.token;

    if (token) {
        request.headers.common.Authorization = `Bearer ${token}`;
    }

    return request;
});

http.interceptors.response.use(
    (response) => response,
    (error) => {
        const { status } = error.response;

        if (status === 401 && store.getters.check) {
            store.commit('logout');

            router.push({ name: 'login' });
        }
    }
);

Vue.prototype.$http = http;

export default http;
