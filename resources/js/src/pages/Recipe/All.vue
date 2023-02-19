<template>
    <div class="col-12">
        <div class="card col-12 mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="text">Başlık</label>
                        <input v-model="filterOptions.title" type="text" class="form-control" id="title" placeholder="Başlığa göre giriniz..">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="status">Durum</label>
                        <select v-model="filterOptions.status" class="form-control" id="status">
                            <option :value="null" selected>Tüm Durumlar</option>
                            <option :value="1">Karşılandı</option>
                            <option :value="0">Bekliyor</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="status">Kategori</label>
                        <select name="category_id" id="category_id" v-model="filterOptions.category_id" class="form-control">
                            <option :value="null">Kategori Seçiniz</option>
                            <option v-for="category in getCategories" :value="category.id" :key="'category_' + category.id">{{ category.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="city">İl</label>
                        <select v-model="filterOptions.address.city" name="city" id="city" class="form-control" @change="onCityChanged">
                            <option :value="null">{{ $t('modules.recipe.form.city.selectTitle') }}</option>
                            <option v-for="cit in address.cities" :key="'city_' + cit.id" :value="cit.id">{{ cit.name }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="district">İlçe</label>
                        <select v-model="filterOptions.address.district" name="district" id="district" class="form-control">
                            <option :value="null">{{ $t('modules.recipe.form.district.selectTitle') }}</option>
                            <option v-for="dis in address.districts" :key="'district_' + dis.id" :value="dis.name">{{ dis.name }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4">
                        <label>&nbsp;</label>
                        <button type="submit" @click="prepareData" class="btn btn-primary col-12">Filtrele</button>
                    </div>
                </div>
            </div>
        </div>
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
            <template #item-address="item">
                {{ item.address?.city?.name }}
            </template>
            <template #expand="item">
                <div style="padding: 15px">
                    <div class="row">
                        <p>{{ item.description }}</p>
                    </div>

                    <div class="row flex-row-reverse flex-md-row">
                        <div class="col-12 col-md-9 mb-3">
                            <ul class="list-group">
                                <li class="list-group-item d-flex" v-for="i in item.items" :key="'item_' + i.id">
                                    <span class="d-inline-flex me-2 badge bg-info">{{ i.count }}</span>
                                    <span class="d-inline-flex">{{ i.name }}</span>
                                    <span class="d-inline-flex ms-auto">{{ i.category?.name }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3">
                            <AddressTable v-if="item.address" :city="item.address.city" :district="item.address.district" :neighbourhood="item.address.neighbourhood" />
                            <p>{{ $t('modules.recipe.form.address_detail.title') }} : {{ item.address?.address_detail }}</p>
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
import emitter from '@/EventBus.js'
import {mapGetters} from "vuex";

export default {
    name: "Recipe.All",
    components: {
        AddressTable,
        'EasyDataTable': Vue3EasyDataTable
    },
    watch:{
        serverOptions(){
            this.prepareData();
        },
    },
    data(){
        return {
            loading: true,
            serverItemsLength: 0,
            serverOptions: {
                page: 1,
                rowsPerPage: 25,
                sortBy: 'age',
                sortType: 'desc',
            },
            headers: [
                { text: "Başlık", value: "title" },
                { text: "Destek Durumu", value: "status" },
                { text: "İstek Zamanı", value: "created_at" },
                { text: "Karşılanma Zamanı", value: "status_updated_at" },
                { text: "İl", value: "address" },
            ],
            items: [],
            filterOptions:{
                status: null,
                category_id: null,
                address: {
                    city: null,
                    district: null
                }
            },
            address: {
                cities: [],
                districts: [],
            }
        }
    },
    computed: {
        ...mapGetters('global', ['getCategories']),
    },
    created() {
        this.getCities()
        this.prepareData()
        emitter.emit('set-title', 'Tüm İstekler')
    },
    methods: {
        support,
        async prepareData(){
            const $this = this
            $this.loading = true;
            const data = {
                filter: $this.filterOptions
            }
            await this.axios.post(`/api/recipes/all?per_page=${this.serverOptions.rowsPerPage}&page=${this.serverOptions.page}`, data)
                .then((response) => {
                    $this.items = response.data.data.data
                    $this.loading = false;
                    $this.serverItemsLength = response.data.data.total;
                })
                .catch((e) => {
                    $this.loading = false;
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        },
        async getCities(){
            await this.axios.get('/api/address/cities')
                .then((response) => {
                    this.address.cities = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                })
        },
        async onCityChanged(){
            this.filterOptions.address.district = null

            if (this.filterOptions.address.city === null){
                this.districts = []
            }else{
                await this.axios.get('/api/address/districts/' + this.filterOptions.address.city)
                    .then((response) => {
                        this.address.districts = response.data.data
                    })
                    .catch((e) => {
                        this.$swal.fire(this.$t('general.error'), e.response.data.message, 'error')
                    })
            }
        },
    },
}
</script>

<style scoped>
.filter-column {
  display: flex;
  align-items: center;
  justify-items: center;
  position: relative;
}
.filter-icon {
  cursor: pointer;
  display: inline-block;
  width: 15px !important;
  height: 15px !important;
  margin-right: 4px;
}
.filter-menu {
  padding: 15px 30px;
  z-index: 1;
  position: absolute;
  top: 30px;
  width: 200px;
  background-color: #fff;
  border: 1px solid #e0e0e0;
}
.filter-age-menu {
  height: 40px;
}
.slider {
  margin-top: 36px;
}
.status-selector {
  width: 100%;
}
</style>
