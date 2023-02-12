<template>
    <div class="row mb-3">
        <div class="col-12">
            <select v-model="city_data" name="city" id="city" class="form-control" @change="onCityChanged">
                <option :value="null">İl Seçiniz</option>
                <option v-for="cit in cities" :key="'city_' + cit.id" :value="cit.id">{{ cit.name }}</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <select v-model="district_data" name="district" id="district" class="form-control" @change="onDistrictChanged">
                <option :value="null">İlçe Seçiniz</option>
                <option v-for="dis in districts" :key="'district_' + dis.id" :value="dis.name">{{ dis.name }}</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <select v-model="neighbourhood_data" name="neighbourhood" id="neighbourhood" class="form-control" @change="onNeighbourhoodChanged">
                <option :value="null">Mahalle Seçiniz</option>
                <option v-for="nei in neighbourhoods" :key="'neighbourhood_' + nei.id" :value="nei.id">{{ nei.name }}</option>
            </select>
        </div>
    </div>
</template>

<script>

export default {
    name: "Address.Form",
    props: {
        city: {
            type: [String, Number, null],
            required: true,
            default: null,
        },
        district: {
            type: [String, Number, null],
            required: true,
            default: null,
        },
        neighbourhood: {
            type: [String, Number, null],
            required: true,
            default: null,
        },
    },
    data(){
        return {
            city_data: null,
            district_data: null,
            neighbourhood_data: null,

            cities: [],
            districts: [],
            neighbourhoods: [],
        }
    },
    created() {
        this.city_data = this.city
        this.district_data = this.district
        this.neighbourhood_data = this.neighbourhood
        this.getCities()
    },
    methods: {
        async getCities(){
            await this.axios.get('/api/address/cities')
                .then((response) => {
                    this.cities = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire('Hata', e.response.data.message, 'error')
                })
        },
        async onCityChanged(){
            this.district_data = null
            this.neighbourhood_data = null

            this.$emit('city', this.city_data)
            await this.axios.get('/api/address/districts/' + this.city_data)
                .then((response) => {
                    this.districts = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire('Hata', e.response.data.message, 'error')
                })
        },
        async onDistrictChanged(){
            this.neighbourhood_data = null
            this.$emit('district', this.district_data)
            await this.axios.get('/api/address/neighbourhoods/' + this.city_data + '/' + this.district_data)
                .then((response) => {
                    this.neighbourhoods = response.data.data
                })
                .catch((e) => {
                    this.$swal.fire('Hata', e.response.data.message, 'error')
                })
        },
        onNeighbourhoodChanged(){
            this.$emit('neighbourhood', this.neighbourhood_data)
        },
    },
    emits: ['city', 'district', 'neighbourhood']
}
</script>

<style scoped>

</style>
