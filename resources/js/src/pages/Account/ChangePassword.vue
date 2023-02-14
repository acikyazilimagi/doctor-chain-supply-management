<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $t('modules.account.edit.title') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <BackendAndFrontendCombined :errors="validation.errors" :message="validation.message" :show="backendAndFrontendCombinedErrorsStatus" :validation-attributes="validation.validationAttributes" :show-header-message="validation.showHeaderMessage" :vuelidate="vuelidate$" />
                        </div>

                    </div>

                    <div class="row mb-3">
                        <label for="old_password" class="col-md-4 col-form-label text-md-end">{{ $t('modules.account.change_password.old_password.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="old_password"
                                type="password"
                                v-model="old_password"
                                class="form-control"
                                name="old_password"
                                :placeholder="$t('modules.account.change_password.old_password.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.old_password.$error,
                                    'is-valid': vuelidate$.old_password.$dirty && !vuelidate$.old_password.$invalid,
                                }"
                                @input="vuelidate$.old_password.$touch()"
                                @focus="vuelidate$.old_password.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.old_password" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ $t('modules.account.change_password.password.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="password"
                                type="password"
                                v-model="password"
                                class="form-control"
                                name="password"
                                :placeholder="$t('modules.account.change_password.password.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.password.$error,
                                    'is-valid': vuelidate$.password.$dirty && !vuelidate$.password.$invalid,
                                }"
                                @input="vuelidate$.password.$touch()"
                                @focus="vuelidate$.password.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.password" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password_confirm" class="col-md-4 col-form-label text-md-end">{{ $t('modules.account.change_password.password_confirmation.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="password_confirm"
                                v-model="password_confirmation"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                :placeholder="$t('modules.account.change_password.password_confirmation.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.password_confirmation.$error,
                                    'is-valid': vuelidate$.password_confirmation.$dirty && !vuelidate$.password_confirmation.$invalid,
                                }"
                                @input="vuelidate$.password_confirmation.$touch()"
                                @focus="vuelidate$.password_confirmation.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.password_confirmation" />
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="button" class="btn btn-primary" @click.prevent="update">{{ $t('general.update') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddressForm from "@/src/components/Address/Form.vue";
import BackendAndFrontendCombined from "@/src/components/ValidationMessages/BackendAndFrontendCombined.vue";
import SingleInputError from "@/src/components/ValidationMessages/SingleInputError.vue";
import useVuelidate from '@vuelidate/core'

import {
    maxLength,
    minLength,
    required, sameAs,
} from '@vuelidate/validators'

export default {
    name: "Account.Edit",
    components: {
        BackendAndFrontendCombined,
        SingleInputError,
        AddressForm,
    },
    data(){
        return {
            form_is_posted: false,

            old_password: null,
            password: null,
            password_confirmation: null,

            validation: {
                errors: [],
                message: '',
                validationAttributes: {
                    old_password: this.$t('modules.account.change_password.old_password.title'),
                    password: this.$t('modules.account.change_password.password.title'),
                    password_confirmation: this.$t('modules.account.change_password.password_confirmation.title'),
                },
                show_backend_and_frontend_combined_error_messages: true,
                showHeaderMessage: false,
            },
        }
    },
    computed: {
        backendAndFrontendCombinedErrorsStatus() {
            return (
                this.form_is_posted &&
                this.validation
                    .show_backend_and_frontend_combined_error_messages
            )
        },
    },
    setup() {
        return { vuelidate$: useVuelidate() }
    },
    validations() {
        return {
            old_password: {
                required,
                minLength: minLength(8),
                maxLength: maxLength(25),
                $autoDirty: true,
                $lazy: true,
            },
            password: {
                required,
                minLength: minLength(8),
                maxLength: maxLength(25),
                $autoDirty: true,
                $lazy: true,
            },
            password_confirmation: {
                required,
                minLength: minLength(8),
                maxLength: maxLength(25),
                sameAs: sameAs(this.password),
                $autoDirty: true,
                $lazy: true,
            },
        }
    },
    methods: {
        async update(e) {
            const $this = this
            e.preventDefault()

            this.form_is_posted = true
            $this.vuelidate$.$touch()
            if ($this.vuelidate$.$pending || $this.vuelidate$.$error) return

            const result = await $this.vuelidate$.$validate()
            if (!result) {
                return
            }

            const data = {
                old_password: this.old_password,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }

            $this.$swal.fire({
                title: this.$t('modules.account.edit.are_you_sure'),
                showCancelButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await $this.axios.put('/api/account/password_reset', data)
                        .then((response) => {
                            if (response.data.status) {
                                $this.vuelidate$.$reset()
                            }
                            this.$swal.fire(response.data.message.title, response.data.message.body, response.data.message.type)
                        })
                        .catch((e) => {
                            if (e.response.status === 422) {
                                this.validation.errors = e.response.data.errors
                                this.validation.message = e.response.data.message
                            } else {
                                this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                            }
                        })
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
