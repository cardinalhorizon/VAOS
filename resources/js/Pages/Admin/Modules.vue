<template>
    <div class="container mx-auto">
        <table class="table">
            <thead>
            <tr>
                <th>Module Name</th>
                <th>Type</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="module in modules"></tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Modules",
    data() {
        return {
            modules: {}
        }
    }
}
</script>

<style scoped>

</style>
