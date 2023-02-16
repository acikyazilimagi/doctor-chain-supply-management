<template>
    <div class="col-12">
        <EasyDataTable
            v-model:server-options="serverOptions"
            :server-items-length="serverItemsLength"
            :loading="loading"
            :headers="headers"
            :items="items"
            buttons-pagination
            :rows-per-page="serverOptions.rowsPerPage"
        >
            <template #item-status="item">
                <span class="badge me-2" :class="{'bg-success': item.status, 'bg-warning': !item.status}">{{ item.status ? 'Karşılandı' : 'Bekliyor' }}</span>
            </template>
            <template #expand="item">
                <div style="padding: 15px">
                    <div class="row">
                        <p>{{ item.description }}</p>
                        <p>{{ $t('modules.recipe.form.address_detail.title') }} : {{ item.address.address_detail }}</p>
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
import { support } from '@/src/helpers.js'
import AddressTable from "@/src/components/Address/Table.vue";

export default {
    name: "Recipe.All",
    components: {
        AddressTable,
        'EasyDataTable': Vue3EasyDataTable
    },
    watch:{
        serverOptions(){
            this.prepareData();
            console.log('serverOptions changed')
        }
    },
    data(){
        return {
            loading: true,
            serverItemsLength: 0,
            serverOptions: {
                page: 1,
                rowsPerPage: 15,
                sortBy: 'age',
                sortType: 'desc',
            },
            headers: [
                { text: "Başlık", value: "title" },
                { text: "Destek Durumu", value: "status" },
            ],
            items: [],
        }
    },
    created() {
        this.prepareData()
    },
    methods: {
        support,
        async prepareData(){
            const $this = this
            $this.loading = true;
            await this.axios.get(`/api/recipes/all?per_page=${this.serverOptions.rowsPerPage}&page=${this.serverOptions.page}`)
                .then((response) => {
                    $this.items = response.data.data.data
                    $this.loading = false;
                    $this.serverItemsLength = response.data.data.total;
                })
                .catch((e) => {
                    $this.loading = false;
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        }
    }
}
</script>

<style scoped>

</style>
