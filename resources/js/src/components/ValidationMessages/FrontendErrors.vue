<template>
    <div v-if="vuelidate && vuelidate.$errors.length" class="alert alert-danger">
        <ul class="list-unstyled mb-0">
            <li v-for="error of vuelidate.$errors" :key="error.$uid">
                {{ attributes[error.$property] ?? error.$property }} -
                {{ error.$message }}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'FrontendErrors',
        props: {
            vuelidate: {
                type: [Object, null],
                default: null,
            },
            messages: {
                type: Object,
                default: function () {
                    return {}
                },
            },
            attributes: {
                type: Object,
                default: () => {
                    return {}
                },
            },
        },
    }
</script>

<style scoped></style>
