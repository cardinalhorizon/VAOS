<template>
    <div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 v-if="route_info.depapt">{{ route_info.depapt.name }}</h3>
                        <div v-if="route_info.depapt" class="form-group">
                            <div><span class="ls-title">ICAO:</span>{{ route_info.depapt.icao }}</div>
                            <div><span class="ls-title">IATA:</span>{{ route_info.depapt.iata }}</div>
                        </div>
                        <h3 v-if="route_info.arrapt">{{ route_info.arrapt.name }}</h3>
                        <div v-if="route_info.arrapt" class="form-group">
                            <div><span class="ls-title">ICAO:</span>{{ route_info.arrapt.icao }}</div>
                            <div><span class="ls-title">IATA:</span>{{ route_info.arrapt.iata }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Route Information</h1>
                        <div>
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-2">
                                    <label for="inputEmail4">Flight Number</label>
                                    <input type="text" v-model="route_info.flightnum" class="form-control" id="inputEmail4" placeholder="1152">
                                </div>
                                <div style="text-overflow: ellipsis !important;" class="form-group">
                                    <div v-for="acf in aircraft_groups" v-bind:key="acf.id">
                                        <input type="radio" v-bind:value="acf.id" v-model="route_info.primary_group.id">
                                        <input v-bind:id="'#'+acf.id" type="checkbox" v-bind:value="acf.id" v-model="route_info.aircraft_groups">
                                        <label v-bind:for="'#'+acf.id">{{acf.icao}} | {{acf.name}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="post" v-bind:action="'/admin/' + route_info.airline.id + '/schedule/'+route_info.id">
                    <input type="hidden" name="_token" v-bind:value="token"/>
                    <input type="hidden" name="_method" value="PATCH"/>
                    <input type="hidden" name="data" v-bind:value="JSON.stringify({ route_info })"/>
                    <button class="btn btn-success" type="submit">Save</button>
                </form>
            </div>
        </div>
        <div class="row">

        </div>
        {{route_info.primary_group}}
    </div>
</template>

<script>
    export default {
        name: "EditRoute",
        data() {
            return {
                route_info: null,
                aircraft_groups: null,
                base_url: Laravel.baseUrl,
                token: Laravel.csrfToken
            }
        },
        props: {
            route: String,
            acfgrp: String
        },
        created() {
            this.route_info = JSON.parse(this.route);
            this.aircraft_groups = JSON.parse(this.acfgrp);
        }
    }
</script>

<style scoped>

</style>