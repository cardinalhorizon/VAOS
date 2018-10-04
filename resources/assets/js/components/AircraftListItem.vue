<template>
    <tr>
        <td>{{acf.registration}}</td>
        <td>{{acf.airline.icao}}</td>
        <td>{{acf.manufacturer}}</td>
        <td>{{acf.icao}}</td>
        <td>{{acf.name}}</td>
        <td v-if="acf.hub !== null">{{acf.hub.airport.icao}}</td>
        <td v-else>Not Assigned</td>
        <td>{{acf.location}}</td>
        <td>
            <span class="badge" v-bind:class="StatusCheck">{{acf.statusText}}</span>
        <td>
            <div class="btn-group" role="group" aria-label="Actions">
                <a v-bind:href="base_url + 'admin/fleet/'+ acf.id + '/edit'" class="btn btn-primary btn-brand"><i class="fa fa-edit"></i></a>
            </div>

        </td>

    </tr>
</template>

<script>
    export default {
        name: "AircraftListItem",
        data() {
            return {
                base_url: Laravel.baseUrl
            }
        },
        props: {
            acf: Object
        },
        computed: {
            StatusCheck: function()
            {
                let status = this.acf.status;
                switch(status)
                {
                    case 0:
                        this.acf.statusText = "Storage";
                        return {
                            'badge-secondary': true
                        };
                    case 1:
                        this.acf.statusText = "Available";
                        return {
                            'badge-primary': true
                        };
                    case 2:
                        this.acf.statusText = "Active";
                        return {
                            'badge-success': true
                        };
                    case 3:
                        this.acf.statusText = "Maintenance";
                        return {
                            'badge-warning': true
                        };
                }
            }
        }
    }
</script>

<style scoped>

</style>