<template>
    <div class="modal fade" id="changeSelector" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Select Current Aviation Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select v-model="selectedAirline" class="custom-select custom-select-lg mb-3">
                        <option v-if="isAdmin" v-bind:value="'all'">Global Scope</option>
                        <template v-if="isAdmin" v-for="airline in airlines">
                            <option v-bind:value="airline">{{airline.name}}</option>
                        </template>
                        <template v-if="!isAdmin" v-for="airline in userAirlines">
                            <option v-bind:value="airline">{{airline.name}}</option>
                        </template>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "NavAirlineSelector",
        data () {
            return {
                airlines: Laravel.airlines,
                userAirlines: Laravel.user.airlines,
                isAdmin: Laravel.user.admin,
            }
        },
        computed: {
            selectedAirline: {
                get () {
                    return this.$store.getters.airline
                },
                set (value) {
                    if (value === 'all') {
                        this.$store.commit('set', null)
                    }
                    else {
                        this.$store.commit('set', value)
                    }
                }
            }
        }

    }
</script>

<style scoped>

</style>