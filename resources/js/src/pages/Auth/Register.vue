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
                        <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.first_name.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="first_name"
                                type="text"
                                v-model="first_name"
                                class="form-control"
                                name="first_name"
                                :placeholder="$t('modules.auth.register.form.first_name.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.first_name.$error,
                                    'is-valid': vuelidate$.first_name.$dirty && !vuelidate$.first_name.$invalid,
                                }"
                                @input="vuelidate$.first_name.$touch()"
                                @focus="vuelidate$.first_name.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.first_name" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.last_name.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="last_name"
                                type="text"
                                v-model="last_name"
                                class="form-control"
                                name="last_name"
                                :placeholder="$t('modules.auth.register.form.last_name.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.last_name.$error,
                                    'is-valid': vuelidate$.last_name.$dirty && !vuelidate$.last_name.$invalid,
                                }"
                                @input="vuelidate$.last_name.$touch()"
                                @focus="vuelidate$.last_name.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.last_name" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.email.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="email"
                                type="email"
                                v-model="email"
                                class="form-control"
                                name="email"
                                :placeholder="$t('modules.auth.register.form.email.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.email.$error,
                                    'is-valid': vuelidate$.email.$dirty && !vuelidate$.email.$invalid,
                                }"
                                @input="vuelidate$.email.$touch()"
                                @focus="vuelidate$.email.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.email" />
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

                    <div class="row mb-3">
                        <label for="specialty" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.specialty.title') }}</label>

                        <div class="col-md-6">
                            <select
                                id="specialty"
                                v-model="specialty"
                                class="form-control"
                                name="specialty"
                                :class="{
                                'is-invalid': vuelidate$.specialty.$error,
                                'is-valid': vuelidate$.specialty.$dirty && !vuelidate$.specialty.$invalid,
                            }"
                                @input="vuelidate$.specialty.$touch()"
                                @focus="vuelidate$.specialty.$touch()"
                            >
                                <option :value="null">{{ $t('general.select') }}</option>
                                <option v-for="s in specialties" :value="s.id" :key="'specialty_' + s.id">{{ s.name }}</option>
                            </select>
                            <SingleInputError :vuelidate-object="vuelidate$.specialty" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ $t('modules.auth.register.form.referral_code.title') }}</label>

                        <div class="col-md-6">
                            <input
                                id="referral_code"
                                type="text"
                                v-model="referral_code"
                                class="form-control"
                                name="referral_code"
                                :placeholder="$t('modules.auth.register.form.referral_code.placeholder')"
                                :class="{
                                    'is-invalid': vuelidate$.referral_code.$error,
                                    'is-valid': vuelidate$.referral_code.$dirty && !vuelidate$.referral_code.$invalid,
                                }"
                                @input="vuelidate$.referral_code.$touch()"
                                @focus="vuelidate$.referral_code.$touch()"
                            >
                            <SingleInputError :vuelidate-object="vuelidate$.referral_code" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input
                                    id="legal_text"
                                    v-model="legal_text"
                                    type="checkbox"
                                    class="form-check-input"
                                    name="legal_text"
                                    :class="{
                                        'is-invalid': vuelidate$.legal_text.$error,
                                        'is-valid': vuelidate$.legal_text.$dirty && !vuelidate$.legal_text.$invalid,
                                    }"
                                    @input="vuelidate$.legal_text.$touch()"
                                    @focus="vuelidate$.legal_text.$touch()"
                                >

                                <label class="form-check-label" for="legal_text" v-html-safe="$t('modules.auth.register.form.legal_text.placeholder', {link: legalPageLink})"></label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="button" class="btn btn-danger me-2" @click.prevent="reset">{{ $t('general.reset') }}</button>
                            <button type="button" class="btn btn-primary" @click.prevent="register">{{ $t('general.register') }}</button>
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

import { required, minLength, maxLength, email, sameAs } from '@/src/helpers/TranslatedVuelidateValidators.js'

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

            first_name: null,
            last_name: null,
            email: null,
            password: null,
            password_confirmation: null,
            specialty: null,
            legal_text: null,
            referral_code: null,

            specialties: [],

            validation: {
                errors: [],
                message: '',
                validationAttributes: {
                    first_name: this.$t('modules.auth.register.form.first_name.title'),
                    last_name: this.$t('modules.auth.register.form.last_name.title'),
                    email: this.$t('modules.auth.register.form.email.title'),
                    password: this.$t('modules.auth.register.form.password.title'),
                    password_confirmation: this.$t('modules.auth.register.form.password_confirmation.title'),
                    specialty: this.$t('modules.auth.register.form.specialty.title'),
                    legal_text: this.$t('modules.auth.register.form.legal_text.title'),
                    referral_code: this.$t('modules.auth.register.form.referral_code.title'),
                },
                show_backend_and_frontend_combined_error_messages: true,
                showHeaderMessage: false,
            },
        }
    },
    created() {
        this.prepareSpecialties()

        const referral_code = this.$router.currentRoute.value.query?.referral_code
        if (referral_code){
            this.referral_code = referral_code
        }

        emitter.emit('set-title', 'Kayıt Ol')
    },
    computed: {
        backendAndFrontendCombinedErrorsStatus() {
            return (
                this.form_is_posted &&
                this.validation
                    .show_backend_and_frontend_combined_error_messages
            )
        },
        legalPageLink(){
            const url = this.$router.resolve({ name: 'LegalText' }).fullPath
            return "<a target='_blank' href=\"" + url + "\">KVKK ve Aydınlatma Metni'ni</a>"
        }
    },
    setup() {
        return { vuelidate$: useVuelidate() }
    },
    validations() {
        return {
            first_name: {
                minLength: minLength(2),
                maxLength: maxLength(30),
                required,
                $autoDirty: true,
                $lazy: true,
            },
            last_name: {
                minLength: minLength(2),
                maxLength: maxLength(30),
                required,
                $autoDirty: true,
                $lazy: true,
            },
            email: {
                email,
                maxLength: maxLength(60),
                required,
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
            specialty: {
                required,
                $autoDirty: true,
                $lazy: true,
            },
            legal_text: {
                sameAs: sameAs(true),
                $autoDirty: true,
                $lazy: true,
            },
            referral_code: {
                minLength: minLength(16),
                maxLength: maxLength(16),
                required,
                $autoDirty: true,
                $lazy: true,
            },
        }
    },
    methods: {
        async prepareSpecialties(){
            const $this = this
            await this.axios.get('/api/specialties')
                .then((response) => {
                    $this.specialties = response.data.data
                })
                .catch((e) => {
                    $this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                })
        },
        reset() {
            this.first_name = null
            this.last_name = null
            this.email = null
            this.password = null
            this.password_confirmation = null
            this.specialty = null
            this.legal_text = null
            this.referral_code = null

            this.vuelidate$.$reset()
            this.form_is_posted = false
        },
        async register(e) {
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
                first_name: this.first_name,
                last_name: this.last_name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                specialty: this.specialty,
                legal_text: this.legal_text,
                referral_code: this.referral_code,
            }

            $this.$swal.fire({
                title: this.$t('modules.account.edit.are_you_sure'),
                showCancelButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await $this.axios.post('/api/auth/register', data)
                        .then((response) => {
                            this.$swal
                                .fire(response.data.message.title, response.data.message.body, response.data.message.type)
                                .then(() => {
                                    if (response.data.status) {
                                        this.$router.push({name:"Auth.Login"})
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
