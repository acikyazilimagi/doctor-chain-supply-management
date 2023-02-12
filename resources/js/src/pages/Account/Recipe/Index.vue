<template>
    <div class="col-12">
        <h1>{{ $t('modules.recipe.title.all_my_recipes') }}</h1>
        <List :recipes="recipes" />
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
            recipes: [],
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
                    $this.recipes = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                })
        }
    }
}
</script>

<style scoped>

</style>
