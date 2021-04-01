<template>
    <div>
        <v-navigation-drawer dark app width="300px" v-model="drawer">
            <v-img :aspect-ratio="16/9" src="/img/vaos_df_bg-01.svg" gradient="rgba(100,115,201,.33), rgba(25,32,72,.7)">
                <v-layout pa-2 column align-center fill-height class="lightbox white--text">
                    <v-spacer></v-spacer>
                    <v-avatar size="84">
                        <img src="https://pbs.twimg.com/profile_images/1151671407228706822/UZxOws2b_400x400.jpg" alt="John" style="border: white 3px solid;">
                    </v-avatar>
                    <v-flex shrink style="text-align:center">
                        <div class="headline">Taylor Broad</div>
                        <div class="subtitle-1">BossOfGames | Senior Captain</div>
                    </v-flex>
                </v-layout>
            </v-img>
            <SideNavFlightsCard></SideNavFlightsCard>
            <v-list flat>
                <v-list-item link to="/">
                    <v-list-item-icon>
                        <v-icon>dashboard</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="profile">
                    <v-list-item-icon>
                        <v-icon>person</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="map">
                    <v-list-item-icon>
                        <v-icon>map</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Live Map</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <!--
            <template slot="append">
                <v-layout pa-2 column align-center fill-height>
                    <img src="/img/vaos_logo.svg" width="80%"/>
                </v-layout>
            </template>
            -->
        </v-navigation-drawer>
        <v-app-bar app>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>{{ comm_name }}</v-toolbar-title>
        </v-app-bar>
        <v-content style="min-height: 100vh;">
            <slot></slot>
        </v-content>
        <v-footer app>
            VAOS Classic 2.1 | Material Crew Redux
        </v-footer>
    </div>
</template>

<script>
    import SideNavMenu from "../../components/SideNavMenu";
    import SideNavFlightsCard from "../../components/SideNavFlightsCard";
    export default {
        name: "MainLayout",
        components: {SideNavFlightsCard, SideNavMenu},
        created() {
            console.log(this.$vuetify.breakpoint.lgAndUp);
            this.drawer = !!this.$vuetify.breakpoint.lgAndUp;
        },
        data() {
            return {
                comm_name: window.appSettings.community_name,
                drawer: false,
                items: [
                    {
                        icon: "dashboard",
                        title: "Dashboard"
                    },
                    {
                        icon: "person",
                        title: "Profile"
                    },
                    {
                        icon: "settings",
                        title: "Settings"
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>
