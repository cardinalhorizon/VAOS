<template>
    <div class="row">
        <div v-for="airline in airlines" v-bind:key="airline.id" class="col xl12 l12 m12 s12">
            <a class="text-white modal-trigger" style="color: white;" href="">
                <div class="card hoverable" style="height: 175px; background-size: cover; border-right: #6aff9a 20px solid; border-radius: 2px 5px 5px 2px">
                    <div style="position: absolute; height: 100%; width: 100%; background-color: rgba(25,25,25,.65);z-index: 0"></div>
                    <div class="card-content" style="position: relative; z-index: 5;height:175px;display:block;">
                        <div style="font-size: 2.4rem; font-weight: bold; line-height: 3rem;">{{ airline.name }}</div>
                        <div style="color: #ddd; font-size: 1.4rem; position: relative; margin-top: 65px; bottom: 0; width: 100%; font-weight: normal; display: flex;justify-content: space-between;">
                            <span><i class="material-icons" style="font-size: 1.4rem;">flight</i> 172R</span>
                        </div>
                    </div>
                    <div class="card-action">

                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AirlineList",

    }
</script>

<style scoped>

</style>