import Vue from 'vue';
import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.prototype.$http = axios;
