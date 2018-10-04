

/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('aircraft-list', require('./components/AircraftList.vue'));
Vue.component('aircraft-list-item', require('./components/AircraftListItem.vue'));
Vue.component('side-nav-controls', require('./components/SideNavControls'));
Vue.component('nav-airline-selector', require('./components/NavAirlineSelector'));
Vue.component('create-aircraft', require('./components/CreateAircraft'));
Vue.component('schedule-list', require('./components/ScheduleList'));

import activeAirline from './store/activeAirline';

const app = new Vue({
    el: '#app',
    store:
        activeAirline

});
