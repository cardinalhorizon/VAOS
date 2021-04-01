/*
 * Application Main Entry Point
 */


import Vue from 'vue'
import Vuetify from 'vuetify'
import store from './store'
import App from './App'
import router from './router'
import axios from 'axios'

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


import 'vuetify/dist/vuetify.min.css'

import mainLayout from './views/Layouts/MainLayout'
import Blank from "./views/Layouts/Blank";
Vue.component('main-layout', mainLayout);
Vue.component('blank-layout', Blank);

Vue.use(Vuetify);

const vuetify = new Vuetify({
    theme: {
        dark: true,
        themes: {
            dark: {
                primary: window.appSettings.colors.primary
            }
        },
        options: {
            customProperties: true
        }
    },
});

new Vue({
    router,
    store,
    vuetify,
    created()
    {
        console.log("Application Booted.");
    },
    mounted(){

    },
    render: h => h(App)
}).$mount('#app');
