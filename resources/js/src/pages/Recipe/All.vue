<template>
    <div class="col-12">
        <EasyDataTable
            v-model:server-options="serverOptions"
            :server-items-length="serverItemsLength"
            :loading="loading"
            :headers="headers"
            :items="items"
        >
            <template #expand="item">
                <div style="padding: 15px">
                    <div class="row">
                        <p>{{ item.description }}</p>
                        <p>{{ $t('modules.recipe.address_detail.title') }} : {{ item.address.address_detail }}</p>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="i in item.items" :key="'item_' + i.id"><span class="badge bg-info p-2 d-inline-flex">{{ i.count }}</span> {{ i.name }}</li>
                            </ul>
                        </div>
                        <div class="col-4">
                            <AddressTable v-if="item.address" :city="item.address.city" :district="item.address.district" :neighbourhood="item.address.neighbourhood" />
                        </div>
                    </div>
                </div>
            </template>
        </EasyDataTable>
    </div>
</template>

<script>
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import AddressTable from "@/src/components/Address/Table.vue";

export default {
    name: "Recipe.All",
    components: {
        AddressTable,
        'EasyDataTable': Vue3EasyDataTable
    },
    data(){
        return {
            loading: true,
            serverItemsLength: 0,
            serverOptions: {
                page: 1,
                rowsPerPage: 5,
                sortBy: 'age',
                sortType: 'desc',
            },
            headers: [
                { text: "Başlık", value: "title" },
            ],
            items: [],
        }
    },
    created() {
        this.prepareData()
    },
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    },
    methods: {
        async prepareData(){
            const $this = this
            $this.loading = true;
            await this.axios.get('/api/recipes/all')
                .then((response) => {
                    $this.items = response.data.data.data
                    $this.loading = false;
                    $this.serverItemsLength = response.data.data.total;
                })
                .catch((e) => {
                    $this.loading = false;
                    this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                })
        }
    }
}
</script>

<style scoped>

</style>
