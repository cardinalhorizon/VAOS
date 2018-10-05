<template>
    <div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Airport <i class="fa fa-spinner fa-spin float-right" v-show="loading"></i> </h1>
                        <div class="alert alert-danger" v-if="httpError">ERROR: {{ httpError.data.message }}</div>
                        <div class="form-group">
                            <label for="dicao">Departure Airport ICAO</label>
                            <input class="form-control" v-model="depicao" id="dicao" type="text" @input="httpError = null" @keyup.tab="depAptPull" @keyup.enter="depAptPull">
                        </div>
                        <div class="form-group">
                            <label for="aicao">Arrival Airport ICAO</label>
                            <input class="form-control" v-model="arricao" id="aicao" type="text" @input="httpError = null" @keyup.tab="arrAptPull" @keyup.enter="arrAptPull">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 v-if="depapt">{{ depapt.name }}</h3>
                        <div class="form-group">
                            {{ depapt }}
                        </div>
                        <h3 v-if="arrapt">{{ arrapt.name }}</h3>
                        <div class="form-group">
                            {{ arrapt }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <h1>Routes<button style="float: right;" class="btn btn-success" @click="addRoute" v-bind:disabled="airports"><i class="fa fa-plus"></i></button></h1>
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info" v-if="routes.length === 0">To Add Aircraft, Click <i class="fa fa-plus"></i></div>
                        <div v-for="(route, index) in routes" :key="index">
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-2">
                                    <label for="inputEmail4">Flight Number</label>
                                    <input type="text" v-model="route.flightnum" class="form-control" id="inputEmail4" placeholder="1152">
                                </div>
                                <div style="text-overflow: ellipsis !important;" class="form-group col-sm-6 col-md-4">
                                    <label>Groups: <span v-for="e in route.aircraft_groups">{{ e.icao + " "}}</span></label><br>
                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Aircraft Groups
                                    </button>
                                    <div class="dropdown-menu" style="background: #333; padding: 5px;" aria-labelledby="dropdownMenu">
                                        <div v-for="acf in aircraft_groups" v-bind:key="acf.id">
                                            <input type="radio" v-bind:value="acf" v-model="route.primary_group">
                                            <input v-bind:id="'#'+acf.id" type="checkbox" v-bind:value="acf" v-model="route.aircraft_groups">
                                            <label v-bind:for="'#'+acf.id">{{acf.icao}} | {{acf.name}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 col-md-2">
                                    <label>Departure Airport</label>
                                    <h2>{{route.depapt.ident}}</h2>
                                </div>
                                <div class="form-group col-sm-12 col-md-2">
                                    <label>Arrival Airport</label>
                                    <h2>{{route.arrapt.ident}}</h2>
                                </div>
                                <div class="form-group col-sm-12 col-md-2">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <button class="btn btn-primary btn-brand" @click="createReturn(route)" data-toggle="tooltip" data-placement="bottom" title="Create Return"><i class="fa fa-arrow-left"></i></button>
                                        <button class="btn btn-danger btn-brand" @click="removeRoute(index)" data-toggle="tooltip" data-placement="bottom" title="Remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="post" v-bind:action="base_url + '/admin/' + airline.id + '/schedule/'">
                    <input type="hidden" name="_token" v-bind:value="token"/>
                    <input type="hidden" name="data" v-bind:value="JSON.stringify({ airline, depapt, arrapt, routes })"/>
                    <button class="btn btn-primary" type="submit">Add Routes</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "CreateRoute",
        data ()
        {
            return {
                routes: [],
                depicao: null,
                arricao: null,
                depapt: null,
                arrapt: null,
                base_url: Laravel.baseUrl,
                token: Laravel.csrfToken,
                aircraft_groups: null,
                httpError: null,
                loading: false,
            }
        },
        props: {
            acfgrp: String
        },
        mounted() {
            this.aircraft_groups = JSON.parse(this.acfgrp);
        },
        methods: {
            depAptPull() {
                this.loading = true;
                axios.get('https://fsvaos.net/api/data/airports', {
                    params: {
                        icao: this.depicao
                    }
                }).then(response => {
                    this.loading = false;
                    this.depapt = response.data;
                }).catch(error => {
                    this.loading = false;
                    this.httpError = error.response;
                });
            },
            arrAptPull() {
                this.loading = true;
                axios.get('https://fsvaos.net/api/data/airports', {
                    params: {
                        icao: this.arricao
                    }
                }).then(response => {
                    this.loading = false;
                    this.arrapt = response.data;
                }).catch(error => {
                    this.loading = false;
                    this.httpError = error.response;
                });
            },
            addRoute() {
                this.routes.push({
                    flightnum: null,
                    depapt: this.depapt,
                    arrapt: this.arrapt,
                    primary_group: null,
                    aircraft_groups: [],
                    route: null,
                    deptime: null,
                    arrtime: null
                });
            },
            removeRoute(reg) {
                this.routes.splice(reg, 1);
            },
            createReturn(reg) {
                this.routes.push({
                    flightnum: null,
                    depapt: reg.arrapt,
                    arrapt: reg.depapt,
                    primary_group: reg.primary_group,
                    aircraft_groups: reg.aircraft_groups,
                    route: null,
                    deptime: null,
                    arrtime: null
                });
            }
        },
        computed: {
            hubs () {
                return this.$store.getters.airline.hubs;
            },
            airline() {
                return this.$store.getters.airline;
            },
            airports() {
                if (this.depapt && this.arrapt)
                {
                    return false;
                }
                else
                {
                    return true;
                }
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