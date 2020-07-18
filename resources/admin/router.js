
import Vue from 'vue'
import Router from 'vue-router'
import store from '../store'

import Dashboard from '../views/Dashboard'
import FlightListView from "../views/Flights/FlightListView";
import FlightDetailedView from "../views/Flights/FlightDetailedView";
import Login from "../views/Auth/Login";
import ProfileIndexView from "../views/Profile/ProfileIndexView";
import SettingsView from "../views/SettingsView";
import NewFlightSelection from "../views/Flights/NewFlightSelection";
import ProfileLogbookView from "../views/Profile/ProfileLogbookView";
import ProfileHangerView from "../views/Profile/ProfileHangerView";
import PersonalAircraftDetailedView from "../views/Aircraft/PersonalAircraftDetailedView";
import ProfileStatsView from "../views/Profile/ProfileStatsView";
import iFrameContainerView from "../views/iFrameContainerView";
import InterfaceSettingsAdminView from "../views/Admin/InterfaceSettingsAdminView";
import ScheduleView from "../views/Schedule/ScheduleView";
import FirstRunView from "./views/FirstRunView";
import UsersView from "./views/Users/UsersView";

Vue.use(Router);

function doAuth(to, from, next) {

}
export default new Router({
    routes: [
        { path: '/first_run', name: 'first_run', component: FirstRunView },
        // Fleet Management
        { path: '/aircraft', name: 'aircraft', component: AircraftView },
        { path: '/aircraft/:id', name: 'aircraft_detailed', component: AircraftView },
        // Flight Management and Browsing System
        { path: '/flights/new', name: 'new_flight', component: NewFlightSelection },
        { path: '/flights/upcoming', name: 'upcoming_flights', component: FlightListView },
        { path: '/flights/:id', name: 'flight_detailed', component: FlightDetailedView },
        { path: '/schedule', name: 'schedule', component: ScheduleView },
        { path: '/trips', name: 'trips', component: FlightListView },
        // Type Ratings System
        { path: '/type_ratings', name: 'type_ratings'},
        // User Profiles
        { path: '/users', name: 'users', component: UsersView },
        { path: '/users/:id', name: 'profile', component: ProfileIndexView },
        { path: '/users/:id/logbook', name: 'profile_logbook', component: ProfileLogbookView },
        { path: '/users/:id/hanger', name: 'profile_hanger', component: ProfileHangerView},
        { path: '/users/:id/stats', name: 'profile_stats', component: ProfileStatsView},
        { path: '/users/:id/hanger/:aircraft', name: 'aircraft_detailed', component: PersonalAircraftDetailedView},
        { path: '/settings', name: 'settings', component: SettingsView},
        { path: '/settings/admin', name: 'settings_admin', component: InterfaceSettingsAdminView},
        { path: '/map', name: 'map', component: iFrameContainerView, meta: {frameUrl: "https://simvector.cardinalhorizon.com/"}}
    ]
});
