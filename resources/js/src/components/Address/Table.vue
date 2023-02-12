<template>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>İl</td>
            <td>{{ city?.name }}</td>
        </tr>
        <tr>
            <td>İlçe</td>
            <td>{{ district }}</td>
        </tr>
        <tr>
            <td>Mahalle</td>
            <td>{{ neighbourhood?.name }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>

export default {
    name: "Address.Table",
    props: {
        city: {
            type: [String, Number, null],
            required: true
        },
        district: {
            type: [String, Number, null],
            required: true
        },
        neighbourhood: {
            type: [String, Number, null],
            required: true
        },
    }
}
</script>

<style scoped>

</style>
