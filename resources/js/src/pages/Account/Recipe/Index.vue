<template>
    <div class="col-12">
        <h1>{{ $t('modules.recipe.title.all_my_recipes') }}</h1>

        <template v-if="recipes.isLoading">Kullanıcı verisi alınıyor..</template>
        <template v-else>
            <template v-if="recipes.data.status === false">
                <div class="alert alert-danger">{{ recipes.data.message.body }}</div>
            </template>
            <template v-else>
                <List :recipes="recipes.data.data" />
            </template>
        </template>
    </div>
</template>

<script>
import List from "@/src/components/Recipe/List.vue";
import emitter from '@/EventBus.js'

export default {
    name: "Account.Recipe.Index",
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
    },
    methods: {
        async prepareData(){
            const $this = this
            await this.axios.get('/api/account/recipes/my')
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
