<template>
  <div>
    <table class="table table-responsive-sm table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th>Username</th>
          <th>Callsign</th>
          <th>Departure</th>
          <th>Arrival</th>
          <th>Aircraft</th>
          <th>Departure (UTC)</th>
          <th>Arrival (UTC)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody v-for="route in group.data" v-bind:key="route.id">
        <tr>
          <td>{{route.flightnum}}</td>
          <td>{{route.depapt.icao}}</td>
          <td>{{route.arrapt.icao}}</td>
          <td>
            <span v-for="acf in route.aircraft_group" v-bind:key="acf.id">
              <span v-if="acf.pivot.primary === 1" class="text-primary">
                <b>{{ acf.icao }}</b>
              </span>
              <span v-else>{{ acf.icao }}</span>
            </span>
          </td>
          <td>NONE</td>
          <td>{{ route.deptime }}</td>
          <td>{{ route.arrtime }}</td>
          <td>
            <div class="btn-group" role="group" aria-label="Actions">
              <a
                v-bind:href="base_url + 'admin/schedule/'+ route.id + '/edit'"
                class="btn btn-primary btn-brand"
              >
                <i class="fa fa-edit"></i>
              </a>
              <a
                v-bind:href="base_url + 'admin/schedule/'+ route.id + '/edit'"
                class="btn btn-danger btn-brand"
              >
                <i class="fa fa-times"></i>
              </a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "LoggedFlightList",
  data() {
    return {
      flights: null,
      base_url: Laravel.baseUrl
    };
  },
  created() {
    axios
      .get(this.base_url + "/api/v1/flights", {
        params: {
          filter: "active"
        }
      })
      .then(response => {
        this.activeFlights = response.data;
      });
  }
};
</script>

<style scoped>
</style>