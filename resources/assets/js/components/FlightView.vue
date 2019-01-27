<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <div class="flight-panel">
                    <div class="row">
                        <div class="header-bar col-lg-4">
                            <img v-if="flight_obj.airline" v-bind:src="flight_obj.airline.widget" class="widget-img">
                            <div class="header-data">
                                <div class="airline-top" v-if="flight_obj.airline">{{flight_obj.airline.name}} | {{flight_obj.flightnum}}</div>
                                <div class="flight-callsign">{{flight_obj.callsign}}</div>
                            </div>
                        </div>
                        <div class="col-lg-8" style="border-left: 5px solid white; padding-left: 0;">
                            <div class="grid-container">
                                <div class="grid-widget">
                                    <div><i class="fa fa-plane"></i>: {{ flight_obj.aircraft.icao }} | {{ flight_obj.aircraft.registration }}</div>
                                </div>
                                <div class="grid-widget" style="font-size: 4rem;text-align: center">
                                    <div>{{ flight_obj.depapt.icao}}<i class="fa fa-arrow-right"></i>{{flight_obj.arrapt.icao}}</div>
                                </div>
                                <div class="prog status-green" style="width: 55%">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FlightView",
        data()
        {
            return {
                loaded:false,
                flight_obj:null
            }
        },
        props: {
            flight: String
        },
        created() {
            this.flight_obj = JSON.parse(this.flight);
        }
    }
</script>

<style scoped>
    .prog {
        height: 3rem;
        margin-top: auto;
        grid-area: progress;
    }
    .grid-container {

        height: 100%;
        grid-template-columns: 20% 1fr 20%;
        display: grid;
        grid-column-gap: 20px;
        grid-row-gap: 0;
        justify-items: stretch;
        grid-template-areas:
            "data deparr ."
            "progress progress progress";
    }
    .grid-widget {
        padding: 1rem;
        display: table-cell;
        vertical-align: middle;
    }
    .flight-panel {
        background: #333;
        border-radius: .5rem;
        overflow: hidden;
    }
    .airline-top {
        text-transform: uppercase;
    }
    .header-data {
        position: relative;
        padding-left: 1rem;
    }
    .status-green {
        background: #2EBE5D;
    }
    .widget-img {
        height: 100%;
        top:0;
        position: absolute;
        opacity: .5;
    }
    .header-bar {
        padding: 1rem;
        overflow: hidden;
    }
    .flight-callsign {
        font-size: 4rem;
    }
    .flight-progress {

    }
</style>