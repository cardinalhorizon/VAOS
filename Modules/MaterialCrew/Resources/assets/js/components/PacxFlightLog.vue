<template>
    <div>
        <div class="white-text card-offset-title">TFDi PACX Flight Report</div>
        <div v-if="!url">
            <div class="card grey darken-4">
                <div class="card-content">
                    <div class="input-field col s6">
                        <input id="first_name" v-model="report_url" type="text" class="validate">
                        <label for="first_name">Flight Report URL</label>
                    </div>
                    <form method="post" v-bind:action="'/flightops/pacx'">
                        <input type="hidden" name="_token" v-bind:value="csrfToken"/>
                        <input type="hidden" name="data" v-bind:value="JSON.stringify({ report_url, flight })"/>
                        <div style="margin: 1rem auto; text-align: center;">
                            <button class="btn" type="submit">Add Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="flight_log">
            <div class="row" v-if="flight_log">
                <div class="col l6 s12">
                    <div class="card grey darken-4 white-text">
                        <div class="card-content">
                            <ul class="collection with-header">
                                <li class="collection-item"><div>Satisfaction<div class="secondary-content">{{ flight_log.SatisfactionString._text }}</div></div></li>
                                <li class="collection-item"><div>Est Departure<div class="secondary-content">{{ flight_log.EstimatedDepartureTime._text }}</div></div></li>
                                <li class="collection-item"><div>Act Departure<div class="secondary-content">{{ flight_log.ActualDepartureTime._text }}</div></div></li>
                                <li class="collection-item"><div>Est Flight<div class="secondary-content">{{ flight_log.EstimatedFlightTime._text }}</div></div></li>
                                <li class="collection-item"><div>Act Flight<div class="secondary-content">{{ flight_log.ActualFlightTime._text }}</div></div></li>
                                <li class="collection-item"><div>Est Arrival<div class="secondary-content">{{ flight_log.EstimatedArrivalTime._text }}</div></div></li>
                                <li class="collection-item"><div>Act Arrival<div class="secondary-content">{{ flight_log.ActualArrivalTime._text }}</div></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col l6 s12">
                    <div class="card grey darken-4 white-text">
                        <div class="card-content">
                            <h4>Comments</h4>
                            <p v-for="c in flight_log.Comments.Comment">{{c._text }}</p>
                        </div>
                    </div>
                </div>
                <div class="col l10 offset-l1">
                    <div class="card-offset-title">Passenger Manifest</div>
                    <div class="card grey darken-4 white-text" style="max-height: 400px; overflow-y: scroll;">
                        <div class="card-content">
                            <table>
                                <thead>
                                <tr>
                                    <td>First Name</td>
                                    <td>Last Name</td>
                                    <td>Age</td>
                                    <td>Trip Purpose</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="p in flight_log.Passengers.Passenger">
                                    <td>{{ p.FirstName._text }}</td>
                                    <td>{{ p.LastName._text }}</td>
                                    <td>{{ p.Age._text }}</td>
                                    <td>{{ p.TripPurpose._text }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import x2js from 'xml-js'
    export default {
        name: "PacxFlightLog",
        data() {
            return {
                flight_log: null,
                report_url: "",
                csrfToken: window.Laravel.csrfToken
            }
        },
        created() {
            if (this.url) {
                axios.get(this.url+'/xml').then(res => {
                    let log = x2js.xml2js(res.data, {compact: true});
                    console.log(log);
                    this.flight_log = log.PACX.FlightReport;
                });
            }
        },
        props: {
            url: String,
            flight: String
        }
    }
</script>

<style scoped>

</style>