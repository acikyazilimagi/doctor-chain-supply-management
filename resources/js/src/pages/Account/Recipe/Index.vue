<template>
    <div class="col-12">
        <h1>{{ $t('modules.recipe.title.all_my_recipes') }}</h1>
        <List :recipes="getMyRecipes" />
    </div>
</template>

<script>
import List from "@/src/components/Recipe/List.vue";
import emitter from '@/EventBus.js'
import { mapGetters, mapActions } from 'vuex'

export default {
    name: "Account.Recipe.Index",
    components: {
        List,
    },
    computed: {
        ...mapGetters('recipe', ['getMyRecipes'])
    },
    created() {
        this.fetchMyRecipes()
        emitter.on('support-saved', () => this.fetchMyRecipes())
    },
    methods: {
        ...mapActions('recipe', ['fetchMyRecipes']),
    }
}
</script>

<style scoped>

</style>
