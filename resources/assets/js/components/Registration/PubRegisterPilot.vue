<template>
    <div class="row" style="max-width: 800px;">
            <div class="row">
                <div class="input-field col s6">
                    <input id="first_name" v-model="userData.first_name" type="text" class="validate">
                    <label for="first_name">First Name</label>
                </div>
                <div class="input-field col s6">
                    <input id="last_name" v-model="userData.last_name" type="text" class="validate">
                    <label for="last_name">Last Name</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="username" v-model="userData.username" type="text" class="validate">
                    <label for="username">Username</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="email" type="email" v-model="userData.email" class="validate">
                    <label for="email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="password" type="password" v-model="userData.password" class="validate">
                    <label for="password">Password</label>
                </div>
            </div>
            <h3>External Hours <a style="float:right;" class="btn brand b-green" @click="addHours()"><i class="material-icons">add</i></a></h3>
            <div v-for="(hrs, index) in externalHours" :key="index">
                <div class="row">
                    <div class="input-field col s3">
                        <input v-bind:id="index+'name'" placeholder="Spark Virtual" v-model="hrs.name" type="text" class="validate">
                        <label class="active" for="password">Name</label>
                    </div>
                    <div class="input-field col s2">
                        <input v-bind:id="index+'hours'" placeholder="50" v-model="hrs.total" type="text" class="validate">
                        <label class="active" for="password">Hours</label>
                    </div>
                    <div class="input-field col s7">
                        <input v-bind:id="index+'source'" placeholder="https://source.url" v-model="hrs.source_url" type="text" class="validate">
                        <label class="active" for="password">Source</label>
                    </div>
                </div>
            </div>
        <div style="width: 100%; height: 5px; background: #61c7ff;"></div>
        <form method="post" v-bind:action="base_url + '/register'">
            <input type="hidden" name="_token" v-bind:value="token"/>
            <input type="hidden" name="data" v-bind:value="JSON.stringify({ userData, externalHours })"/>
            <div style="margin: 1rem auto; text-align: center;">
                By creating an account, you accept our<br>
                <a v-bind:href="base_url + 'tos'" target="_blank">Terms Of Service</a> and <a v-bind:href="base_url + 'privacy'" target="_blank">Privacy Policy</a>.
            </div>
            <div style="margin: 1rem auto; text-align: center;">
                <button class="btn" type="submit">Create Account</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "pub-register-pilot",
        data() {
            return {
                userData: {
                    first_name: null,
                    last_name: null,
                    username: null,
                    email: null,
                    password: null,
                    newsletter: true,
                    vatsim: null,
                    ivao: null
                },
                externalHours: [],
                base_url: Laravel.baseUrl,
                token: Laravel.csrfToken
            }
        },
        methods: {
            addHours() {
                this.externalHours.push({
                    name: null,
                    total: null,
                    source_url: null
                });
            },
            removeHours(reg) {
                this.externalHours.splice(reg, 1);
            }
        }
    }
</script>

<style scoped>

</style>