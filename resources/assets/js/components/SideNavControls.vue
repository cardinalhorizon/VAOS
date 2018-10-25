<template>
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" v-bind:href="base_url + '/admin'">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <button class="nav-link nav-link-info" v-bind:style="{'background-color': activeAirline.color_primary}" style="width: 100%; border: none; text-align: left;" data-toggle="modal" data-target="#changeSelector">
                    <i class="nav-icon fas fa-globe"></i> {{ activeAirline.name }}
                </button>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fa fa-plane"></i> Fleet</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/fleet'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Groups</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon fas fa-project-diagram"></i> Schedule</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/schedule'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Route Management</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon far fa-building"></i> Airports</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/airports'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Airport Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/hubs'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Hub Management</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#">
                    <i class="nav-icon far fa-map"></i> Flights</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/map'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Live Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/flights'" target="_top">
                            <i class="nav-icon fa fa-caret-right"></i> Flight Management</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/typeratings'">
                    <i class="nav-icon far fas fa-certificate"></i> Type Ratings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="nav-icon far far fa-id-badge"></i> Pilots</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:href="base_url + '/admin/'+ activeAirline.id +'/events'">
                    <i class="nav-icon far fa-calendar-alt"></i> Events</a>
            </li>
            <template v-if="systemAdmin">
                <li class="nav-title">Administration</li>
                <li class="nav-item">
                    <a class="nav-link" v-bind:href="base_url + '/admin/airlines'">
                        <i class="nav-icon fa fa-globe"></i>Aviation Groups</a>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="nav-icon far fa-user"></i> Users</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" v-bind:href="base_url + '/admin/users'" target="_top">
                                <i class="nav-icon fa fa-caret-right"></i> Manage Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_top">
                                <i class="nav-icon fa fa-caret-right"></i> Permissions</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="charts.html">
                        <i class="nav-icon fa fa-cog"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="charts.html">
                        <i class="nav-icon fa fa-puzzle-piece"></i> Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="charts.html">
                        <i class="nav-icon fa fa-info"></i> About</a>
                </li>
            </template>
        </ul>
    </nav>
</template>

<script>

    export default {
        name: "SideNavControls",
        data ()
        {
            return {
                base_url: Laravel.baseUrl,
                user: Laravel.user,
                systemAdmin: Laravel.user.admin,
                showChange: false
            }
        },
        beforeMount() {
            // determine system level perms.
        },
        computed: {
            activeAirline () {
                return this.$store.getters.airline;
            }
        }
    }
</script>

<style scoped>

</style>