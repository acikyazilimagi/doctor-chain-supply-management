<template>
    <div class="row mb-3" v-for="item in recipeData" :key="index" >
        <div class="col-10 col-md-11">
            <div class="row">
                <div class="col-6">
                    <input id="text" v-model="item.text" type="text" class="form-control" placeholder="İhtiyaç adı/detayı">
                </div>
                <div class="col-3">
                    <select name="category_id" id="category_id" v-model="item.category_id" class="form-control">
                        <option :value="null">{{ $t('general.select') }}</option>
                        <option v-for="category in categories" :value="category.id" :key="'category_' + category.id">{{ category.value }}</option>
                    </select>
                </div>
                <div class="col-3">
                    <input id="count" v-model="item.count" type="number" class="form-control" name="count" placeholder="Adet" min="1">
                </div>
            </div>
        </div>
        <div class="col-2 col-md-1">
            <button v-if="item.action_type" type="button" class="btn btn-success col-12" @click.prevent="addNewRecipeItem()">+</button>
            <button v-else type="button" class="btn btn-danger col-12" @click.prevent="removeRecipeItem(item.order)">-</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "RecipeFormItem",
    props: {
        recipeData: {
            type: Array,
            required: true
        }
    },
    data(){
        return {
            recipeData: [
                {
                    order: 1,
                    text: null,
                    count: 1,
                    category_id: null,
                    action_type: true
                },
            ],
            category_id_: null,
            categories: []
        }
    },
    created() {
        this.getRecipeItemCategories()
    },
    methods: {
        async getRecipeItemCategories(){
            await this.axios.get('/api/recipes/items/categories')
                .then((response) => {
                    this.categories = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                })
        },
        update(){
            this.$emit('action', {
                action: 'update',
                order : this.order,
                text: this.text_,
                count: this.count_,
                category_id: this.category_id_,
            })
        },
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

<style scoped>

</style>
