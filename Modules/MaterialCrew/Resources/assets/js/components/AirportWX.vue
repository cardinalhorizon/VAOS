<template>
    <div class="card">
        {{ metar_raw }}
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
                error: false
            }
        },
        props: {
            icao: String
        },
        mounted() {
            axios.get('http://avwx.rest/api/metar/' + this.icao).then((result) => {
                console.log(result);
                this.metar_raw = result['data']['Raw-Report'];
                this.flight_rules = result['data']['Flight-Rules'];
            }).catch(error => {
                console.log('Error Retrieving Airport: ' + this.icao);
                this.error = true;
            });
        }
    }
</script>

<style scoped>

</style>