import Vue from "vue";
import axios from "axios";

export default {
    state: {
        user: null,
        token: Vue.$cookies.get("token")
    },

    getters: {
        user: state => (state.user ? state.user : []),
        token: state => state.token,
        check: state => state.user !== null
    },

    mutations: {
        saveToken(state, { token, remember }) {
            state.token = token;

            Vue.$cookies.set("token", token, {
                expires: remember ? 365 : null
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

            Vue.$cookies.remove("token");
        }
    },

    actions: {
        async fetchUser({ commit, state }) {
            try {
                const { data } = await axios.get("/api/user", {
                    headers: { Authorization: `Bearer ${state.token}` }
                });

                commit("fetchUserSuccess", { user: data });
            } catch (error) {
                commit("fetchUserFailure");
            }
        },

        async logout({ commit, state }) {
            try {
                await axios.post("/api/logout", {}, {
                    headers: { Authorization: `Bearer ${state.token}` }
                });
            } catch (error) {}

            commit("logout");
        }
    }
};
