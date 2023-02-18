<template>
    <div class="accordion">
        <div v-for="recipe in recipes" class="accordion-item">
            <h2 class="accordion-header" :id="'recipe_' + recipe.id">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#recipe_container_' + recipe.id" aria-expanded="false" :aria-controls="'recipe_container_' + recipe.id">
                    <span v-if="recipe.status === 1" class="me-2 text-danger">Karşılanma Zamanı : {{ recipe.status_updated_at }}</span>
                    <span class="badge me-2" :class="{'bg-success': recipe.status, 'bg-warning': !recipe.status}">{{ recipe.status ? 'Karşılandı' : 'Bekliyor' }}</span>
                    <span>{{ recipe.title }}</span>
                    <a v-if="!recipe.status && getUser && recipe.created_by === getUser.id" @click.prevent="support(recipe)" class="btn btn-sm btn-info d-inline-flex ms-auto">İhtiyacımı Karşıladım</a>
                </button>
            </h2>
            <div :id="'recipe_container_' + recipe.id" class="accordion-collapse collapse" :aria-labelledby="'recipe_container_' + recipe.id" :data-bs-parent="'#recipe_' + recipe.id">
                <div class="accordion-body">
                    <div class="row">
                        <p>Açıklama : {{ recipe.description }}</p>
                        <p>{{ $t('modules.recipe.form.address_detail.title') }} : {{ recipe.address?.address_detail }}</p>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <ul class="list-group">
                                <li class="list-group-item d-flex" v-for="item in recipe.items" :key="'item_' + item.id">
                                    <span class="d-inline-flex me-2 badge bg-info">{{ item.count }}</span>
                                    <span class="d-inline-flex">{{ item.name }}</span>
                                    <span class="d-inline-flex ms-auto">{{ item.category?.name }}</span>
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
import { support } from '@/src/helpers.js'
import AddressTable from "@/src/components/Address/Table.vue";
import { mapGetters } from 'vuex';

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
        ...mapGetters('auth', [
            'getUser'
        ]),
    },
    methods: {
        support
    },
    emits: ['refresh_data']
}
</script>

<style scoped>

</style>
