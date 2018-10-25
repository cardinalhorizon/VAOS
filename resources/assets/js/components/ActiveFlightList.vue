<template>
    <div class="row">
        <div v-if="!activeFlights" style="text-align: center">
            <h2>Retrieving Flight Info</h2>
            <h1><i class="fa fa-spinner fa-spin"></i></h1>
        </div>
        <div v-for="flight in activeFlights" v-bind:key="flight.id" class="col-lg-6 col-md-6 col-sm-12">
            <FlightCard v-bind:flight="flight"></FlightCard>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import FlightCard from "./Sub-Components/FlightCard";
    export default {
        name: "ActiveFlightList",
        components: {FlightCard},
        data(){
            return {
                activeFlights: null,
                base_url: Laravel.baseUrl
            }
        },
        methods: {
            GetUpdate() {
                axios.get(this.base_url+"/api/v1/flights", {
                    params: {
                        filter: "active"
                    }
                }).then(response => {
                    this.activeFlights = response.data;
                });
            }
        },
        created() {
            // Retrieve all active flights
            axios.get(this.base_url+"/api/v1/flights", {
                params: {
                    filter: "active"
                }
            }).then(response => {
                this.activeFlights = response.data;
            });
            setInterval(function () {
                this.GetUpdate();
            }.bind(this), 15000);
        }
    }
</script>

<style scoped>

</style>