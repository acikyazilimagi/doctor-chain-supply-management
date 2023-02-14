<template>
    <div class="row mb-3" v-for="(item,index) in recipeData" :key="index">
        <div class="col-10 col-md-11">
            <div class="row">
                <div class="col-6">
                    <input
                        id="text"
                        v-model="item.text"
                        type="text"
                        class="form-control"
                        placeholder="İhtiyaç adı/detayı"
                        :class="{
                            'is-invalid': vuelidate$.text.$error,
                            'is-valid': vuelidate$.text.$dirty && !vuelidate$.text.$invalid,
                        }"
                        @input="vuelidate$.text.$touch()"
                        @focus="vuelidate$.text.$touch()"
                    >
                </div>
                <div class="col-3">
                    <select
                        name="category_id"
                        id="category_id"
                        v-model="item.category_id"
                        class="form-control"
                        :class="{
                            'is-invalid': vuelidate$.category_id.$error,
                            'is-valid': vuelidate$.category_id.$dirty && !vuelidate$.category_id.$invalid,
                        }"
                        @input="vuelidate$.category_id.$touch()"
                        @focus="vuelidate$.category_id.$touch()"
                    >
                        <option :value="null">{{ $t('general.select') }}</option>
                        <option v-for="category in recipeCategories" :value="category.id" :key="'category_' + category.id">{{ category.value }}</option>
                    </select>
                </div>
                <div class="col-3">
                    <input
                        id="count"
                        v-model="item.count"
                        type="number"
                        class="form-control"
                        name="count"
                        placeholder="Adet"
                        min="1"
                        :class="{
                            'is-invalid': vuelidate$.count.$error,
                            'is-valid': vuelidate$.count.$dirty && !vuelidate$.count.$invalid,
                        }"
                        @input="vuelidate$.count.$touch()"
                        @focus="vuelidate$.count.$touch()"
                    >
                </div>
            </div>
        </div>
        <div class="col-2 col-md-1">
            <button v-if="index === recipeData.length-1" type="button" class="btn btn-success col-5 me-1" @click.prevent="addNewRecipeItem()">+</button>
            <button type="button" class="btn btn-danger col-5" @click.prevent="removeRecipeItem(item.order)">-</button>
        </div>
    </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import {maxLength, minLength, required} from "@vuelidate/validators";

export default {
    name: "RecipeFormItem",
    setup() {
        return { vuelidate$: useVuelidate() }
    },
    validations() {
        return {
            text: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(200),
                $autoDirty: true,
                $lazy: true,
            },
            category_id: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(200),
                $autoDirty: true,
                $lazy: true,
            },
            count: {
                required,
                minLength: minLength(2),
                maxLength: maxLength(200),
                $autoDirty: true,
                $lazy: true,
            },
        }
    },
    props: {
        recipeData: {
            type: Array,
            default: () => []
        },
        recipeCategories: {
            type: Array,
            default: () => []
        }
    },
    emits: ["recipeData"],
    methods: {
        addNewRecipeItem(){
            this.recipeData.push({
                order: this.recipeData[this.recipeData.length-1].order + 1,
                action_type: false,
                text: null,
                count: 1,
                category_id: null
            })
            this.$emit('recipeData', this.recipeData)
        },
        removeRecipeItem(value){
            this.$swal.fire({
                title: this.$t('general.are_you_sure'),
                icon: 'question',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = this.recipeData.find(item => item.order === value)
                    this.recipeData.splice(this.recipeData.indexOf(data), 1)
                }
            })
        }
    },
}
</script>
