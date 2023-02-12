<template>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ $t('modules.account.edit.title') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <BackendAndFrontendCombined :errors="validation.errors" :message="validation.message" :show="backendAndFrontendCombinedErrorsStatus" :validation-attributes="validation.validationAttributes" :vuelidate="vuelidate$" />
                        </div>

                    </div>

                    <div class="row mb-3">
                        <label for="specialty" class="col-md-4 col-form-label text-md-end">{{ $t('modules.recipe.form.specialty.title') }}</label>

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
    required,
    requiredIf,
} from '@vuelidate/validators'
import {mapActions} from "vuex";

export default {
    name: "Account.Edit",
    components: {
        BackendAndFrontendCombined,
        SingleInputError,
        AddressForm,
    },
    data(){
        return {
            form_is_loading: true,
            form_is_posting: false,
            form_is_posted: false,

            specialty: null,

            specialties: [],

            validation: {
                errors: [],
                message: '',
                validationAttributes: {
                    specialty: this.$t('modules.auth.register.form.specialty.title'),
                },
                show_backend_and_frontend_combined_error_messages: true,
            },
        }
    },
    created() {
        this.prepareSpecialties()
        this.specialty = this.user.specialty.id
    },
    computed: {
        backendAndFrontendCombinedErrorsStatus() {
            return (
                this.form_is_posted &&
                this.validation
                    .show_backend_and_frontend_combined_error_messages
            )
        },
        user() {
            return this.$store.getters['auth/user']
        },
    },
    setup() {
        return { vuelidate$: useVuelidate() }
    },
    validations() {
        return {
            specialty: {
                required,
                $autoDirty: true,
                $lazy: true,
            },
        }
    },
    methods: {
        ...mapActions({
            resetUser:'auth/resetUser'
        }),
        selectFile(event) {
            this.file = event.target.files[0];
        },
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

            const headers = { 'Content-Type': 'multipart/form-data' };
            let formData = new FormData()
            formData.append('specialty', this.specialty)

            $this.$swal.fire({
                title: this.$t('modules.account.edit.are_you_sure'),
                showCancelButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await $this.axios.post('/api/account/update', formData, { headers })
                        .then((response) => {
                            if (response.status) {
                                this.resetUser()
                                this.$swal.fire(this.$t('general.success'), response.data.message, 'success')
                            } else {
                                this.$swal.fire(this.$t('general.error'), response.data.message, 'danger')
                            }
                        })
                        .catch((e) => {
                            if (e.statusCode === 422) {
                                state.validationProp.errors = e.data.errors
                                state.validationProp.message = e.data.message
                            } else {
                                this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
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
