<template>
    <div class="card brand">
        <div class="card-content" v-if="flight_info">
            <div style="margin-bottom: .5rem; font-size: 1.6rem;">{{flight_info.flight}}</div>
            <table>
                <tbody>
                <tr>
                    <td>Speed</td>
                    <td>{{flight_info.gs}}</td>
                </tr>
                <tr>
                    <td>Altitude</td>
                    <td>{{flight_info.altitude}}</td>
                </tr>
                <tr>
                    <td>Lat</td>
                    <td>{{flight_info.lat}}</td>
                </tr>
                <tr>
                    <td>Lon</td>
                    <td>{{flight_info.lon}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "FlightWidget",
        data () {
            return {
                flight_info: null,
                base_url: Laravel.baseUrl
            }
        },
        props: {
            flight: String
        },
        methods: {
            GetUpdate: function() {
                axios.get(this.base_url + '/flightops/flights/' + this.flight + '/telemetry').then((result) => {
                    this.flight_info = result['data'];
                }).catch(error => {
                    console.log('Error Retrieving Flight: ' + this.flight);
                    this.error = true;
                });
            }
        },
        created() {
            axios.get(this.base_url + '/flightops/flights/' + this.flight + '/telemetry').then((result) => {
                console.log(result);
                this.flight_info = result['data'];
            }).catch(error => {
                console.log('Error Retrieving Flight: ' + this.flight);
                this.error = true;
            });
            setInterval(function () {
                this.GetUpdate();
            }.bind(this), 15000);
        }

    }
</script>

<style scoped>

</style>