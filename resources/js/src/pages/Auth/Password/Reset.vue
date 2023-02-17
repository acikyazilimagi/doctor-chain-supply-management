<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $t('general.register') }}</div>

                <div class="card-body">
                   <div class="row">
                       <div class="col-12">
                           <BackendAndFrontendCombined :errors="validation.errors" :message="validation.message" :show="backendAndFrontendCombinedErrorsStatus" :validation-attributes="validation.validationAttributes" :show-header-message="validation.showHeaderMessage" :vuelidate="vuelidate$" />
                       </div>

                   </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.email.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="email"
                                type="email"
                                :value="email"
                                class="form-control"
                                disabled
                                name="email"
                                :placeholder="$t('modules.auth.register.form.email.placeholder')"
                            >
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.password.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="password"
                                type="password"
                                v-model="password"
                                class="form-control"
                                name="password"
                                :placeholder="$t('modules.auth.register.form.password.placeholder')"
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
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.password_confirmation.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="password-confirm"
                                v-model="password_confirmation"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                :placeholder="$t('modules.auth.register.form.password_confirmation.placeholder')"
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
                            <button type="button" class="btn btn-danger me-auto" @click.prevent="reset">{{ $t('general.reset') }}</button>
                            <button type="button" class="btn btn-primary" @click.prevent="resetPassword">Güncelle</button>
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
import emitter from '@/EventBus.js'

import {
    required,
    minLength,
    maxLength,
    email,
    sameAs,
} from '@vuelidate/validators'

export default {
    name: "Auth.Register",
    components: {
        BackendAndFrontendCombined,
        SingleInputError,
        AddressForm,
    },
    data(){
        return {
            form_is_posted: false,

            email: null,
            password: null,
            password_confirmation: null,
            token: null,

            validation: {
                errors: [],
                message: '',
                validationAttributes: {
                    email: this.$t('modules.auth.register.form.email.title'),
                    password: this.$t('modules.auth.register.form.password.title'),
                    password_confirmation: this.$t('modules.auth.register.form.password_confirmation.title'),
                },
                show_backend_and_frontend_combined_error_messages: true,
                showHeaderMessage: false,
            },
        }
    },
    created() {
        this.token = this.$router.currentRoute.value.params.token
        this.email = this.$router.currentRoute.value.query.email

        emitter.emit('set-title', 'Parola Yenile')
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
        reset() {
            this.password = null
            this.password_confirmation = null

            this.vuelidate$.$reset()
            this.form_is_posted = false
        },
        async resetPassword(e) {
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
                token: this.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }

            $this.$swal.fire({
                title: this.$t('modules.account.edit.are_you_sure'),
                showCancelButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await $this.axios.post('/api/auth/password/reset', data)
                        .then((response) => {
                            this.$swal
                                .fire(response.data.message.title, response.data.message.body, response.data.message.type)
                                .then(() => {
                                    if (response.data.status) {
                                        $this.$router.push({name:"Auth.Login"})
                                    }
                                })
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
