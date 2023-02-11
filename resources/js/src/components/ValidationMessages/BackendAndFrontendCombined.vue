<template>
    <template v-if="show">
        <BackendErrors :errors="errors" :message="message" class="mb-2"></BackendErrors>
        <FrontendErrors :attributes="validationAttributes" :vuelidate="vuelidate"></FrontendErrors>
    </template>
</template>

<script>
    import BackendErrors from './BackendErrors.vue'
    import FrontendErrors from './FrontendErrors.vue'

    export default {
        name: 'BackendAndFrontendCombined',
        components: {
            BackendErrors,
            FrontendErrors,
        },
        props: {
            show: {
                type: Boolean,
                default: true,
            },
            errors: {
                type: Array,
                default: () => [],
            },
            message: {
                type: String,
                default: '',
            },
            vuelidate: {
                type: [Object, null],
                default: null,
            },
            validationAttributes: {
                type: Object,
                default: () => {
                    return {}
                },
            },
        },
    }
</script>
