<template>
    <div class="row mb-3">
        <div class="col-12">
            <select v-model="city_data" name="city" id="city" class="form-control" @change="onCityChanged">
                <option :value="null">İl Seçiniz</option>
                <option value="1">Adana</option>
                <option value="2">Bursa</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <select v-model="district_data" name="district" id="district" class="form-control" @change="onDistrictChanged">
                <option :value="null">İlçe Seçiniz</option>
                <option value="11">İlçe 1</option>
                <option value="22">İlçe 2</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <select v-model="neighbourhood_data" name="neighbourhood" id="neighbourhood" class="form-control" @change="onNeighbourhoodChanged">
                <option :value="null">Mahalle Seçiniz</option>
                <option value="111">Mahalle 1</option>
                <option value="222">Mahalle 2</option>
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
            neighbourhood_data: null
        }
    },
    created() {
        this.city_data = this.city
        this.district_data = this.district
        this.neighbourhood_data = this.neighbourhood
    },
    methods: {
        onCityChanged(){
            this.$emit('city', this.city_data)
        },
        onDistrictChanged(){
            this.$emit('district', this.district_data)
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
