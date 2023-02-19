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
import VueSecureHTML from 'vue-html-secure';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import VueCookieComply from 'vue-cookie-comply'
import 'vue-cookie-comply/dist/style.css'

const app = createApp(App);

app.config.globalProperties.$safeHTML = VueSecureHTML.safeHTML;
app.config.globalProperties.$escapeHTML = VueSecureHTML.escapeHTML;
app.config.globalProperties.$removeHTML = VueSecureHTML.removeHTML;

app.use(router)
app.use(VueAxios, axios)
app.use(VueSweetalert2);
app.use(i18n)
app.use(store)
app.use(VueSecureHTML)
app.use(VueCookieComply)

// Object.entries(import.meta.glob('./src/**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

app.mount('#app');
