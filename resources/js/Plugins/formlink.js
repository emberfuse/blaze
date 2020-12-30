import axios from './http';
import Vue from 'vue';
import Form from 'formlink';

Vue.use(Form, { data: { axios } });
