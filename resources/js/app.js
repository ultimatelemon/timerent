import {createApp} from "vue/dist/vue.esm-bundler";
import Vuex from 'vuex';


// Initialize the app
const app = createApp({});

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
if(isApplication(window.location.href)) {
    window.axios.defaults.baseURL = window.location.origin + '/api';
    window.axios.defaults.headers.common['Authorization'] = ' Bearer ' + window.localStorage.getItem('tr_member_auth_token');
} else {
    window.axios.defaults.baseURL = import.meta.env.VITE_API_URL;
    window.axios.defaults.headers.common['Authorization'] = ' Bearer ' + window.localStorage.getItem('tr_auth_token');

}
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;

axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if(error.response.status === 401 && window.location !== '/login') {
        window.localStorage.removeItem('tr_auth_token');
        window.location = '/login';
    }
    return Promise.reject(error);
})

import * as VueRouter from 'vue-router'
import {routes} from "./routes.js";
// import nl from "./nl.js";


/**
 * Future translations of the application
 */
// const i18n = new VueI18n({
//     locale: 'nl',
//     nl,
// })

/**
 * Store some data
 */
app.use(Vuex)
import {store} from './store.js';
app.use(store);
import lodash from "lodash";


/**
 * Routes are stored in routes.js
 */
const router = VueRouter.createRouter({
    mode: 'history',
    history: VueRouter.createWebHistory(),
    routes: isApplication(window.location.href) ? ApplicationRoutes : routes,
});


router.beforeEach((to, from, next) => {
    // store.commit('setTitle', to.meta.title ? i18n.t(to.meta.title) : "")
    next();
});
app.use(router)

/**
 * Import components
 * @type {Record<string, () => Promise<string extends keyof KnownAsTypeMap ? KnownAsTypeMap[string] : unknown>>}
 */
const files = import.meta.glob('./**/*.vue');
for (const file in files) {
    app.component(file.split('.').pop().split('.')[0], files[file]);
}

import Sidebar from "./pages/Components/Sidebar.vue";
import {isApplication} from "./utilities.js";
import {ApplicationRoutes} from "./ApplicationRoutes.js";
import {DateTime} from "luxon";
// import {isVenue} from "./Utils.js";
// import {VenueRoutes} from "./VenueRoutes.js";
app.component('side-bar', Sidebar)

/**
 * Use lodash for magic calculation shit
 * @type {_.LoDashStatic | _ | *}
 * @private
 */
app.prototype_ = lodash

app.config.globalProperties.$filters = {
    humanDate(value) {
        value = new Date(value).toISOString();
        const months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
        let month = months[(DateTime.fromISO(value).month - 1)];
        let date = DateTime.fromISO(value);
        return `${date.day} ${month} ${date.year}`;
    },

    humanDateTime(value) {
        value = new Date(value).toISOString();
        const months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
        let month = months[(DateTime.fromISO(value).month - 1)];
        let date = DateTime.fromISO(value);
        let minute = date.minute;
        let hour = date.hour;
        if(date.minute <= 9) minute = date.minute+"0";
        if(date.hour <= 9) hour = "0"+date.hour;
        return `${date.day} ${month} ${date.year} om ${hour}:${minute}`;
    },

    humanTime(value) {
        value = new Date(value).toISOString();
        let date = DateTime.fromISO(value);
        let minute = date.minute;
        let hour = date.hour;
        if(date.hour <= 9) hour = "0"+date.hour;
        if(date.minute <= 9) minute = "0"+date.minute;
        return `${hour}:${minute}`
    },

    firstLetterUppercase(value) {
        return value.charAt(0).toUpperCase() + value.slice(1);
    },

    currency(value, currency = 'euro') {
        if (typeof value !== "number") {
            return value;
        }
        let cur = '';
        switch(currency) {
            case 'euro':
                cur = '€'
                break;

            default:
                cur = '$';
                break;
        }

        return cur + (value / 100).toFixed(2).replace('.', ',')
    },

    test (value) {
        value = new Date(value).toISOString()
    }
}

/**
 * Beautiful charts
 */
import VueApexCharts from "vue3-apexcharts";
app.use(VueApexCharts)

app.component('apexchart', VueApexCharts)

app.mount('#app');