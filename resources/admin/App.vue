<template>
    <v-app id="inspire" dark>
        <component :is="layout">
            <router-view/>
        </component>
    </v-app>
</template>

<script>
    const default_layout = "main";
    export default {
        computed: {
            layout() {
                return (this.$route.meta.layout || default_layout) + '-layout';
            }
        }
    }
</script>
