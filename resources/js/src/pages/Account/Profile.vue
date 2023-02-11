<template>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Adı</td>
                <td>{{ user.name }}</td>
            </tr>
            <tr>
                <td>E-Posta</td>
                <td>{{ user.email }}</td>
            </tr>
            <tr>
                <td>Uzmanlık</td>
                <td>{{ user.specialty.name }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>

export default {
    name: "Account.Profile",
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    }
}
</script>

<style scoped>

</style>
