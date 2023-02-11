import _ from 'lodash';
window._ = _;

import 'bootstrap';

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true

import { createApp } from 'vue';
import App from './App.vue';
import router from './router.js'
import VueAxios from 'vue-axios'
import i18n from "./i18n";
import store from "./src/store";

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(App);

app.use(router)
app.use(VueAxios, axios)
app.use(VueSweetalert2);
app.use(i18n)
app.use(store)

// Object.entries(import.meta.glob('./src/**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

app.mount('#app');
