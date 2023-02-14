<template>
    <div v-if="validationErrors || message" class="alert alert-danger">
        <div v-if="showHeaderMessage" class="text-center">{{ message }}</div>
        <template v-if="validationErrors">
            <hr v-if="showHeaderMessage" class="mt-2" />
            <ul class="list-unstyled mb-0">
                <template v-for="(value, key) in validationErrors" :key="'error_main_' + key">
                    <template v-if="typeof value === 'object'">
                        <li v-for="(sub_value, sub_key) in value" :key="'error_sub_' + sub_key">
                            {{ sub_value }}
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            {{ value }}
                        </li>
                    </template>
                </template>
            </ul>
        </template>
    </div>
    <!--<div v-for="(v, k) in validationErrors" :key="k">
		  <p v-for="error in v" :key="error" class="text-sm">
			  {{ error }}
		  </p>
	  </div>-->
</template>

<script>
    export default {
        name: 'BackendErrors',
        props: {
            message: {
                type: [String, null],
                default: null,
            },
            errors: {
                type: Object,
                required: true,
            },
            showHeaderMessage: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {}
        },
        computed: {
            validationErrors() {
                let errors = Object.values(this.errors)
                errors = errors.flat()
                return errors.length ? errors : null
            },
        },
    }
</script>

<style scoped></style>
