<template>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <router-link :to="{ name: 'Recipes.Create'}" class="btn btn-success col-12">İhtiyaç Listesi Oluştur</router-link>
            </div>

            <div class="col-12 mt-3">
                <h2 class="text-center text-danger my-5">Acil Destek Bekleyen İstekler</h2>
                <List :recipes="recipes" @refresh_data="prepareData"/>
            </div>
        </div>
    </div>
</template>

<script>
import List from "@/src/components/Recipe/List.vue";

export default {
    name: "Index",
    components: {
        List,
    },
    data(){
        return {
            recipes: [],
        }
    },
    created() {
        this.prepareData()
    },
    methods: {
        async prepareData(){
            const $this = this
            await this.axios.get('/api/recipes/latests')
                .then((response) => {
                    $this.recipes = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire('Hata', e.response.data.message, 'error')
                })
        }
    }
}
</script>

<style scoped>

</style>
