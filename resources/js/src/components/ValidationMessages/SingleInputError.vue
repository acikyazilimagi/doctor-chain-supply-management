<template>
    <template v-if="'$errors' in vuelidateObject && vuelidateObject.$errors.length">
        <p v-for="(error, i) of vuelidateObject.$errors" :key="'error_' + i" class="text-danger">
            {{ error.$message }}
        </p>
    </template>
</template>

<script>
    export default {
        name: 'SingleInputError',
        props: {
            vuelidateObject: {
                type: Object,
                required: true,
            },
        },
    }
</script>

<style scoped></style>
