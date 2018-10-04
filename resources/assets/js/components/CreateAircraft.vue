<template>
    <div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h1>Aircraft Information <i class="fa fa-spinner fa-spin float-right" v-show="loading"></i> </h1>
                    <div class="alert alert-danger" v-if="httpError">ERROR: {{ httpError.data.message }}</div>
                    <div class="form-group">
                        <label for="icao">ICAO (Press Return for Search)</label>
                        <input class="form-control" v-model="aircraftData.icao" id="icao" type="text" @input="httpError = null" @keyup.tab="pullAircraftFromMaster" @keyup.enter="pullAircraftFromMaster">
                    </div>
                    <div class="form-group">
                        <label for="manufacturer">Manufacturer</label>
                        <input class="form-control" v-model="aircraftData.manufacturer" id="manufacturer" type="text">
                    </div>
                    <div class="form-group">
                        <label for="model">Model</label>
                        <input class="form-control" v-model="aircraftData.name" id="model" type="text">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <h1>Registrations<button style="float: right;" class="btn btn-success" @click="addRegistration"><i class="fa fa-plus"></i></button></h1>
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info" v-if="registrations.length === 0">To Add Aircraft, Click <i class="fa fa-plus"></i></div>
                    <div class="ls-column" v-for="(reg, index) in registrations" :key="index">
                        <div class="form-group">
                            <label for="inputEmail4">Registration</label>
                            <input type="text" v-model="reg.registration" class="form-control" id="inputEmail4" placeholder="N123AB">
                        </div>
                        <div class="form-group">
                            <label for="hub">Hub</label>
                            <select v-model="reg.hub_id" id="hub" class="custom-select">
                                <option v-bind:value="null">Not Assigned</option>
                            <template v-for="hub in hubs">
                                <option v-bind:value="hub.pivot.id">{{hub.icao}} | {{ hub.name }}</option>
                            </template>
                        </select>
                        </div>
                        <div class="form-group" style="display: inline-grid">
                            <button class="btn btn-danger mt-auto" @click="removeRegistration(index)"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <form method="post" v-bind:action="base_url + '/admin/' + airline.id + '/fleet'">
                <input type="hidden" name="_token" v-bind:value="token"/>
                <input type="hidden" name="data" v-bind:value="JSON.stringify({ aircraftData, registrations, airline})"/>
                <button class="btn btn-primary" type="submit">Add Aircraft</button>
            </form>
        </div>
    </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "CreateAircraft",
        data ()
        {
            return {
                registrations: [],
                aircraftData: {
                    icao: null,
                    manufacturer: null,
                    name: null
                },
                base_url: Laravel.baseUrl,
                token: Laravel.csrfToken,
                output: [],
                httpError: null,
                loading: false,
                dataError: false,
            }
        },
        methods: {
            pullAircraftFromMaster() {
                this.loading = true;
                axios.get('https://fsvaos.net/api/data/aircraft', {
                    params: {
                        icao: this.aircraftData.icao
                    }
                }).then(response => {
                    this.loading = false;
                    this.aircraftData.name = response.data.model;
                    this.aircraftData.manufacturer = response.data.manufacturer;
                }).catch(error => {
                    this.loading = false;
                    this.httpError = error.response;
                });
            },
            addRegistration() {
                this.registrations.push({
                    registration: null,
                    hub_id: null
                });
            },
            removeRegistration(reg) {
                this.registrations.splice(reg, 1);
            }
        },
        computed: {
            hubs () {
                return this.$store.getters.airline.hubs;
            },
            airline() {
                return this.$store.getters.airline;
            }
        }
    }
</script>

<style scoped>
    .ls-column {
        grid-template-columns: auto auto auto;
        display: grid;
        grid-column-gap: 20px;
        grid-row-gap: 0;
        justify-items: stretch;
    }
</style>