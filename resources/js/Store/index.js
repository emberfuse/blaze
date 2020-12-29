import Vue from 'vue';
import Vuex from 'vuex';
import data from './store';

Vue.use(Vuex);

const store = new Vuex.Store(data);

export default store;
