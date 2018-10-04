<template>
    <div class="card grey darken-2 white-text">
        <div class="card-content">
            <div class="ctitle" v-bind:class="FlightRules">{{icao}}</div>
            <p>{{ metar_raw }}</p>
            <div v-for="taf in taf_reports" v-bind:key="taf.id">
                <div v-bind:class="ColorCalc(taf['Flight-Rules'])">{{taf['Sanitized']}}</div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        name: "AirportWX",
        data () {
            return {
                metar_raw: null,
                flight_rules: null,
                error: false,
                name_string: null,
                taf_reports: null
            }
        },
        props: {
            icao: String,
            taf: Boolean
        },
        mounted() {
            axios.get('https://avwx.rest/api/metar/' + this.icao).then((result) => {
                this.metar_raw = result['data']['Raw-Report'];
                this.flight_rules = result['data']['Flight-Rules'];
            }).catch(error => {
                console.log('Error Retrieving Airport METAR: ' + this.icao + ". " + error);
                this.error = true;
            });
            axios.get('https://avwx.rest/api/taf/' + this.icao).then((result) => {
                this.taf_reports = result['data']['Forecast'];
            }).catch(error => {
                console.log('Error Retrieving Airport TAF: ' + this.icao + ". " + error);
                this.error = true;
            });
        },
        methods: {
            ColorCalc: function(i) {
                switch (i)
                {
                    case 'VFR':
                        return {
                            'text-vfr': true
                        };
                    case 'MVFR':
                        return {
                            'text-mvfr': true
                        };
                    case 'IFR':
                        return {
                            'text-ifr': true
                        };
                    case 'LIFR':
                        return {
                            'text-lifr': true
                        };
                }
            }
        },
        computed:
        {
            FlightRules: function() {
                switch (this.flight_rules)
                {
                    case 'VFR':
                        return {
                            'bfr-vfr': true
                        };
                    case 'MVFR':
                        return {
                            'bfr-mvfr': true
                        };
                    case 'IFR':
                        return {
                            'bfr-ifr': true
                        };
                    case 'LIFR':
                        return {
                            'bfr-lifr': true
                        };
                }
            }
        }
    }
</script>

<style scoped>
    .ctitle {
        margin-bottom: 1rem;
        font-size: 1.6rem;
    }
    .bfr-vfr {
        border-left: #2EBE5D 5px solid;
        padding-left: 1rem;
    }
    .bfr-mvfr {
        border-left: #4088AF 5px solid;
        padding-left: 1rem;
    }
    .bfr-ifr {
        border-left: #9C222E 5px solid;
        padding-left: 1rem;
    }
    .bfr-lifr {
        border-left: #5932B7 5px solid;
        padding-left: 1rem;
    }
    .text-vfr {
        color: #2EBE5D;
    }
    .text-mvfr {
        color: #4088AF;
    }
    .text-ifr {
        color: #9C222E;
    }
    .text-lifr {
        color: #5932B7;
    }
</style>