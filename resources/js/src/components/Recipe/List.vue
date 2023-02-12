<template>
    <div class="accordion">
        <div v-for="recipe in recipes" class="accordion-item">
            <h2 class="accordion-header" :id="'recipe_' + recipe.id">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#recipe_container_' + recipe.id" aria-expanded="false" :aria-controls="'recipe_container_' + recipe.id">
                    <span>{{ recipe.title }}</span>
                </button>
            </h2>
            <div :id="'recipe_container_' + recipe.id" class="accordion-collapse collapse" :aria-labelledby="'recipe_container_' + recipe.id" :data-bs-parent="'#recipe_' + recipe.id">
                <div class="accordion-body">
                    <div class="row">
                        <p>{{ recipe.description }}</p>
                        <p>{{ $t('modules.recipe.form.address_detail.title') }} : {{ recipe.address?.address_detail }}</p>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="item in recipe.items" :key="'item_' + item.id">
                                    <span class="badge bg-info p-2 d-inline-flex">{{ item.count }}</span>
                                    <span>{{ item.name }}</span>
<!--                                    <span>{{ item.category.name }}</span>-->
                                </li>
                            </ul>
                        </div>
                        <div class="col-4">
                            <AddressTable v-if="recipe.address" :city="recipe.address.city" :district="recipe.address.district" :neighbourhood="recipe.address.neighbourhood" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddressTable from "@/src/components/Address/Table.vue";

export default {
    name: "List",
    components: {
        AddressTable
    },
    props: {
        recipes: {
            type: Array,
            default: [],
        }
    },
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    },
    emits: ['refresh_data']
}
</script>

<style scoped>

</style>
