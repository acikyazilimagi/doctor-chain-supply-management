<template>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td>{{ $t('modules.recipe.form.city.title') }}</td>
            <td>{{ city?.name }}</td>
        </tr>
        <tr>
            <td>{{ $t('modules.recipe.form.city.district') }}</td>
            <td>{{ district }}</td>
        </tr>
        <tr>
            <td>{{ $t('modules.recipe.form.city.neighbourhood') }}</td>
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
            type: [String, Number, null, Object],
            required: true
        },
        district: {
            type: [String, Number, null],
            required: true
        },
        neighbourhood: {
            type: [String, Number, null, Object],
            required: true
        },
    }
}
</script>

<style scoped>

</style>
