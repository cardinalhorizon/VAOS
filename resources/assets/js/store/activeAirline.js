import Vue from 'vue'
import Vuex from 'vuex'
import { VuexPersistence } from 'vuex-persist'

Vue.use(Vuex);

const vuexLocal = new VuexPersistence({
    storage: window.localStorage
});

const activeAirline = new Vuex.Store({
    state: {
        airline: null
    },
    mutations: {
        set(state, airlineID) {
            state.airline = airlineID;
        }
    },
    getters: {
        airline: state => {
            if (state.airline === null) {
                return {
                    id: 'all',
                    name: "All Groups"
                };
            }
            return state.airline;
        }
    },
    plugins: [vuexLocal.plugin]
});

export default activeAirline;