<template>
    <div>
        <div class="row">
            <div class="col-lg-12 hidden-sm" style="display: inline-flex">
                <div style="width:60%">
                    <h3>Filters</h3>
                    <div style="display: flex;justify-content: space-between;">
                        <div class="form-group">
                            <label for="registrationInput">Destination</label>
                            <input v-model="filterDestination" class="form-control" id="registrationInput">
                        </div>
                    </div>
                </div>
                <div style="margin-left: auto; margin-top: auto; margin-bottom: 1rem;">
                    <h3 style="text-align: right">Actions</h3>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a v-bind:href="'/admin/' + activeAirline.id + '/schedule/create'" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="Add New Route"><i class="fa fa-plus"></i></a>
                        <a v-bind:href="'/admin/fleet/create'" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Download"><i class="fa fa-download"></i></a>
                        <a v-bind:href="'/admin/fleet/create'" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="Upload"><i class="fa fa-upload"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div v-for="group in filterList" v-bind:key="group.id">
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" v-bind:data-target="'#'+group.id" aria-expanded="false" aria-controls="collapseTwo">
                            {{ group.icao }} - {{ group.name }}
                        </button>
                        <span style="float: right; margin: auto 0;line-height: 1.5;">{{ group.data.length }}</span>
                    </h5>
                </div>
                <div v-bind:id="group.id" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-responsive-sm table-bordered table-striped table-sm">
                            <thead>
                            <tr>
                                <th>Airline</th>
                                <th>Flight Number</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Aircraft Groups</th>
                                <th>Aircraft</th>
                                <th>Departure (UTC)</th>
                                <th>Arrival (UTC)</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody v-for="route in group.data">
                            <tr>
                                <td>{{route.airline.icao}}</td>
                                <td>{{route.flightnum}}</td>
                                <td>{{route.depapt.icao}}</td>
                                <td>{{route.arrapt.icao}}</td>
                                <td>
                                    <span v-for="acf in route.aircraft_group" v-bind:key="acf.id">
                                        <span v-if="acf.pivot.primary === 1" class="text-primary"><b>{{ acf.icao }}</b></span>
                                        <span v-else>{{ acf.icao }} </span>
                                    </span>
                                </td>
                                <td>NONE</td>
                                <td>{{ route.deptime }}</td>
                                <td>{{ route.arrtime }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a v-bind:href="'/admin/'+ activeAirline.id+'/schedule/'+ route.id + '/edit'" class="btn btn-primary btn-brand"><i class="fa fa-edit"></i></a>
                                        <form method="post" :action="'/admin/schedule/'+ route.id">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button v-bind:href="'/admin/schedule/'+ route.id + '/edit'" type="submit" class="btn btn-danger btn-brand"><i class="fa fa-times"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ScheduleList",
        data() {
            return {
                groups: [],
                base_url: Laravel.baseUrl,
                airline: Laravel.airline_id,
                /* Search Objects */
                filterDestination: "",
                filterLocation: ""
            }
        },
        props: {
            list_data: String
        },
        mounted() {
            this.groups = JSON.parse(this.list_data);
        },
        computed: {
            filterList: function () {
                let destination = this.filterDestination;
                if (destination !== "") {
                    let output = [];
                    console.log(this.groups);
                    this.groups.forEach(function (e) {
                        let acf = e.data.filter(x => x.arrapt.icao.toLowerCase().includes(destination.toLowerCase()));
                        if (acf.length !== 0) {
                            output.push({
                                id: e.id,
                                name: e.name,
                                icao: e.icao,
                                data: acf
                            });
                        }
                    });
                    return output;
                }

                else
                {
                    return this.groups;
                }
            },
            activeAirline () {
                return this.$store.getters.airline;
            }
        },
    }
</script>

<style scoped>
    td {
        vertical-align: middle;
    }

</style>