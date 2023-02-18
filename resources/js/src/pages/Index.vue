<template>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <router-link :to="{ name: 'Account.Recipes.Create'}" class="btn btn-success col-12">İhtiyaç Listesi Oluştur</router-link>
            </div>

            <div class="col-12 colm-md-6">
                <template v-if="recipes.isLoading">İhtiyaç verisi alınıyor..</template>
                <template v-else>
                    <template v-if="recipes.data.status === false">
                        <div class="alert alert-danger">{{ recipes.data.message.body }}</div>
                    </template>
                    <template v-else>
                        <div class="col-12 mt-3">
                            <h2 class="text-center text-danger my-5">Acil Destek Bekleyen İstekler (Son 100 Kayıt)</h2>
                            <List :recipes="recipes.data.data" />
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import List from "@/src/components/Recipe/List.vue";
import emitter from '@/EventBus.js'

export default {
    name: "Index",
    components: {
        List,
    },
    data(){
        return {
            recipes: {
                isLoading: true,
                data: null,
            },
        }
    },
    created() {
        this.prepareData()
        emitter.on('support-saved', () => this.prepareData())
        emitter.emit('set-title', 'Anasayfa')
    },
    methods: {
        async prepareData(){
            const $this = this
            await this.axios.get('/api/recipes/latests')
                .then((response) => {
                    $this.recipes.isLoading = false
                    $this.recipes.data = response.data
                })
                .catch((e) => {
                    $this.recipes.isLoading = false
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        }
    }
}
</script>

<style scoped>

</style>
