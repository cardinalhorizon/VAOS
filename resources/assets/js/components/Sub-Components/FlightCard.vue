<template>
    <a @mouseenter="flipState" @mouseleave="flipState">
        <div class="card" >
            <div v-if="showOptions" class="optiongroup"></div>
            <div class="card-body">
                <div v-if="flight">
                    <h3>{{flight.callsign}}</h3>
                    <div>{{flight.depapt.icao}}-{{flight.arrapt.icao}}</div>
                    <div class="progress-group">
                        <div class="progress-group-header align-items-end">
                            <i class="fa fa-arrow-right progress-group-icon"></i>
                            <div>Progress</div>
                            <div class="ml-auto font-weight-bold mr-2">Phase</div>
                            <div class="text-muted small">({{calcCompleted}}%)</div>
                        </div>
                        <div class="progress-group-bars">
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary" role="progressbar" v-bind:style="calcProgress" v-bind:aria-valuenow="calcProgress" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else style="text-align: center">
                    <h2>Retrieving Flight Info</h2>
                    <h1><i class="fa fa-spinner fa-spin"></i></h1>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
    function distance(lat1, lon1, lat2, lon2, unit) {
        let radlat1 = Math.PI * lat1/180;
        let radlat2 = Math.PI * lat2/180;
        let theta = lon1-lon2;
        let radtheta = Math.PI * theta/180;
        let dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        if (dist > 1) {
            dist = 1;
        }
        dist = Math.acos(dist);
        dist = dist * 180/Math.PI;
        dist = dist * 60 * 1.1515;
        if (unit==="K") { dist = dist * 1.609344 }
        if (unit==="N") { dist = dist * 0.8684 }
        return dist
    }
    export default {
        name: "FlightCard",
        data()
        {
            return {
                showOptions:false
            }
        },
        props: {
            flight: Object
        },
        methods: {
            flipState: function () {
                if(this.showOptions)
                {
                    this.showOptions = false;
                }
                else {
                    this.showOptions = true;
                }
            }
        },
        computed:{
            calcProgress() {
                let leg = distance(this.flight.depapt.lat,this.flight.depapt.lon,this.flight.lat,this.flight.lon, "N");
                let total = distance(this.flight.depapt.lat,this.flight.depapt.lon,this.flight.arrapt.lat,this.flight.arrapt.lon, "N");

                let a = leg/total;
                let total_p = Math.round(a*100);
                return {
                    width: total_p+"%"
                }
            },
            calcCompleted() {
                let leg = distance(this.flight.depapt.lat,this.flight.depapt.lon,this.flight.lat,this.flight.lon, "N");
                let total = distance(this.flight.depapt.lat,this.flight.depapt.lon,this.flight.arrapt.lat,this.flight.arrapt.lon, "N");
                let a = leg/total;
                return Math.round(a*100);
            },
            activeAirline () {
                return this.$store.getters.airline;
            }
        }
    }
</script>

<style scoped>
    .optiongroup {
        position: absolute;
        z-index: 20;
        height: 100%;
        width: 100%;
        padding: 1rem;
        background: rgba(97, 199, 255, .6);
        display: block;
    }
</style>