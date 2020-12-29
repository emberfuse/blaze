import axios from 'axios';
import Cookies from 'js-cookie';

export default {
    state: {
        user: null,
        token: Cookies.get('token'),
    },

    getters: {
        user: (state) => (state.user ? state.user : []),
        token: (state) => state.token,
        check: (state) => state.user !== null,
    },

    mutations: {
        saveToken(state, { token, remember }) {
            state.token = token;

            Cookies.set('token', token, {
                expires: remember ? 365 : null,
            });
        },

        fetchUserSuccess(state, { user }) {
            state.user = user;
        },

        fetchUserFailure(state) {
            state.token = null;
        },

        logout(state) {
            state.user = null;
            state.token = null;

            Cookies.remove('token');
        },
    },

    actions: {
        async fetchUser({ commit }) {
            try {
                await axios.get(route('current.user')).then((response) => {
                    commit('fetchUserSuccess', { user: response.data });
                });
            } catch (error) {
                commit('fetchUserFailure');
            }
        },

        async logout({ commit }) {
            try {
                await axios.post(route('logout'));
            } catch (error) {}

            commit('logout');
        },
    },
};
