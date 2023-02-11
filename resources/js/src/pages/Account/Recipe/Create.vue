<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <BackendAndFrontendCombined
                    :errors="validation.errors"
                    :message="validation.message"
                    :show="backendAndFrontendCombinedErrorsStatus"
                    :vuelidate="vuelidate$"
                    :validation-attributes="validation.validation_attributes"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">İlaç İste</div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-12">
                                <input
                                    id="title"
                                    type="text"
                                    v-model="title"
                                    class="form-control"
                                    name="title"
                                    :placeholder="$t('modules.recipe.form.title.placeholder')"
                                    :class="{
                                        'is-invalid': vuelidate$.title.$error,
                                        'is-valid': vuelidate$.title.$dirty && !vuelidate$.title.$invalid,
                                    }"
                                    @input="vuelidate$.title.$touch()"
                                    @focus="vuelidate$.title.$touch()"
                                >
                                <SingleInputError :vuelidate-object="vuelidate$.title" />
                            </div>
                        </div>

                        <RecipeFormItem v-for="item in items" :text="item.text" :count="item.count" :order="item.order" :action_type="item.action_type" :category_id="item.category_id" @action="catchRecipeFormItemAction"/>

                        <div class="row mb-3">
                            <div class="col-12">
                                <textarea
                                    id="description"
                                    type="text"
                                    v-model="description"
                                    class="form-control"
                                    name="description"
                                    :placeholder="$t('modules.recipe.form.description.placeholder')"
                                    :class="{
                                        'is-invalid': vuelidate$.description.$error,
                                        'is-valid': vuelidate$.description.$dirty && !vuelidate$.description.$invalid,
                                    }"
                                    @input="vuelidate$.description.$touch()"
                                    @focus="vuelidate$.description.$touch()"
                                    rows="3"
                                ></textarea>
                                <SingleInputError :vuelidate-object="vuelidate$.description" />
                            </div>
                        </div>

                        <AddressForm :city="address.city" :district="address.district" :neighbourhood="address.neighbourhood" @city="onCityChanged" @district="onDistrictChanged" @neighbourhood="onNeighbourhoodChanged" />

                        <div class="row mb-3">
                            <div class="col-12">
                                <textarea
                                    id="address_detail"
                                    type="text"
                                    v-model="address_detail"
                                    class="form-control"
                                    name="address_detail"
                                    :placeholder="$t('modules.recipe.form.address_detail.placeholder')"
                                    :class="{
                                        'is-invalid': vuelidate$.address_detail.$error,
                                        'is-valid': vuelidate$.address_detail.$dirty && !vuelidate$.address_detail.$invalid,
                                    }"
                                    @input="vuelidate$.address_detail.$touch()"
                                    @focus="vuelidate$.address_detail.$touch()"
                                    rows="3"
                                ></textarea>
                                <SingleInputError :vuelidate-object="vuelidate$.address_detail" />
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-12">
                                <button type="button" @click.prevent="save" class="btn btn-primary col-12">Kaydet</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import RecipeFormItem from "@/src/components/Recipe/RecipeFormItem.vue";
import AddressForm from "@/src/components/Address/Form.vue";
import BackendAndFrontendCombined from "@/src/components/ValidationMessages/BackendAndFrontendCombined.vue";
import SingleInputError from "@/src/components/ValidationMessages/SingleInputError.vue";
import useVuelidate from '@vuelidate/core'
import {
    required,
    minLength,
    maxLength,
} from '@vuelidate/validators'

export default {
    name: "Account.Recipe.Create",
    components: {
        BackendAndFrontendCombined,
        SingleInputError,
        RecipeFormItem,
        AddressForm,
    },
    data(){
        return {
            form_is_loading: true,
            form_is_posting: false,
            form_is_posted: false,

            title: null,
            items: [
                {
                    order: 1,
                    action_type: true,
                    text: null,
                    count: 1,
                    category_id: null
                }
            ],
            address: {
                city: null,
                district: null,
                neighbourhood: null
            },
            address_detail: null,
            description: null,

            validation: {
                errors: [],
                message: '',
                validation_attributes: {
                    title: "İstek Başlığı",
                    description: "Detaylı Not",
                    city: "İl",
                    district: "İlçe",
                    neighbourhood: "Mahalle",
                    address_detail: "Adres Detayı",
                },
                show_backend_and_frontend_combined_error_messages: true,
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
            title: {
                required,
                minLength: minLength(5),
                maxLength: maxLength(100),
                $autoDirty: true,
                $lazy: true,
            },
            description: {
                maxLength: maxLength(500),
                $autoDirty: true,
                $lazy: true,
            },
            // city: {
            //     required,
            //     $autoDirty: true,
            //     $lazy: true,
            // },
            // district: {
            //     required,
            //     $autoDirty: true,
            //     $lazy: true,
            // },
            // neighbourhood: {
            //     required,
            //     $autoDirty: true,
            //     $lazy: true,
            // },
            address_detail: {
                required,
                maxLength: maxLength(100),
                $autoDirty: true,
                $lazy: true,
            },
        }
    },
    methods: {
        onCityChanged(data){
            this.address.city = data
        },
        onDistrictChanged(data){
            this.address.district = data
        },
        onNeighbourhoodChanged(data){
            this.address.neighbourhood = data
        },
        catchRecipeFormItemAction(data){
            if (data.action === 'add'){
                this.items.push({
                    order: this.items.length + 1,
                    action_type: false,
                    text: null,
                    count: 1,
                    category_id: null
                })
            }
            if (data.action === 'update'){
                let item = this.items.find(item => item.order === data.order)
                item.text = data.text
                item.count = data.count
                item.category_id = data.category_id
            }

            if (data.action === 'remove'){
                this.$swal.fire({
                    title: "Emin Misiniz?",
                    icon: 'question',
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.items.splice(this.items.findIndex(item => item.order === data.order), 1)
                    }
                })
            }
        },
        reset() {
            this.title = null
            this.items = [
                {
                    order: 1,
                    action_type: true,
                    text: null,
                    count: 1,
                    category_id: null
                }
            ]
            this.address = {
                city: null,
                district: null,
                neighbourhood: null
            }
            this.address_detail = null
            this.description = null

            this.vuelidate$.$reset()
            this.form_is_posted = false
        },
        async save(e) {
            const $this = this
            e.preventDefault()

            this.form_is_posted = true
            $this.vuelidate$.$touch()
            if ($this.vuelidate$.$pending || $this.vuelidate$.$error) return

            const result = await $this.vuelidate$.$validate()
            if (!result) {
                return
            }

            $this.$swal.fire({
                title: 'Emin Misiniz?',
                html: '<strong class="text-danger">İsteklerinizi güncelleyemeyeceksiniz !</strong>',
                showCancelButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const data = {
                        title: this.title,
                        items: this.items,
                        address:this.address,
                        address_detail: this.address_detail,
                        description: this.description,
                    }
                    await $this.axios.post('/api/account/recipes', data)
                        .then((response) => {
                            if (response.status) {
                                this.reset()
                                this.$swal.fire('Başarılı', response.data.message, 'success')
                            } else {
                                this.$swal.fire('Hata', response.data.message, 'danger')
                            }
                        })
                        .catch((e) => {
                            if (e.statusCode === 422) {
                                state.validationProp.errors = e.data.errors
                                state.validationProp.message = e.data.message
                            } else {
                                this.$swal.fire('Hata', e.response.data.message, 'error')
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
