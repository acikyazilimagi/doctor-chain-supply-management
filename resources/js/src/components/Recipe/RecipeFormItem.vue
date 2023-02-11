<template>
    <div class="row mb-3">
        <div class="col-10 col-md-11">
            <div class="row">
                <div class="col-6">
                    <input id="text" v-model="text_" @change="update" type="text" class="form-control" name="text" placeholder="İhtiyaç adı/detayı">
                </div>
                <div class="col-3">
                    <select name="category_id" id="category_id" @change="update" v-model="category_id_" class="form-control">
                        <option :value="null">Seçiniz</option>
                        <option v-for="c in categories" :value="c.id" :key="'category_' + c.id">{{ c.value }}</option>
                    </select>
                </div>
                <div class="col-3">
                    <input id="count" v-model="count_" @change="update" type="number" class="form-control" name="count" placeholder="Adet" min="1">
                </div>
            </div>
        </div>
        <div class="col-2 col-md-1">
            <button v-if="action_type" type="button" class="btn btn-success col-12" @click.prevent="add">+</button>
            <button v-else type="button" class="btn btn-danger col-12" @click.prevent="remove(order)">-</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "RecipeFormItem",
    props: {
        action_type: {
            type: Boolean,
            default: true,
        },
        text: {
            type: [String, null],
            required: true
        },
        count: {
            type: Number,
            required: true
        },
        order: {
            type: Number,
            required: true
        },
        category_id: {
            type: [String, Number, null],
            required: true
        },
    },
    data(){
        return {
            text_: null,
            count_: 1,
            category_id_: null,
            categories: []
        }
    },
    created() {
        this.text_ = this.text
        this.count_ = this.count
        this.category_id_ = this.category_id
        this.getRecipeItemCategories()
    },
    methods: {
        async getRecipeItemCategories(){
            await this.axios.get('/api/recipes/items/categories')
                .then((response) => {
                    this.categories = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire('Hata', e.response.data.message, 'error')
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
        add(){
            this.$emit('action', {action: 'add'})
        },
        remove(){
            this.$emit('action', {action: 'remove', order : this.order})
        }
    },
    emits: ['action']
}
</script>

<style scoped>

</style>
