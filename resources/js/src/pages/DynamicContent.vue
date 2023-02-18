<template>
    <div class="container-fluid mt-5">
        <div class="row justify-content-center">
            <template v-if="page.isLoading">Sayfa verisi alınıyor..</template>
            <template v-else>
                <template v-if="page.data.status === false">
                    <div class="alert alert-danger">{{ page.data.message.body }}</div>
                </template>
                <template v-else>
                    <template v-if="page.data.data === null">Sayfa bulunamadı..</template>
                    <template v-else>
                        <h1>{{ page.data.data.title }}</h1>
                        <div v-html-safe="page.data.data.content"></div>
                    </template>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
import List from "@/src/components/Recipe/List.vue";
import emitter from '@/EventBus.js'

export default {
    name: "Index",
    components: {
        List,
    },
    data(){
        return {
            slug: null,
            page: {
                isLoading: true,
                data: null,
            },
        }
    },
    created() {
        this.slug = this.$router.currentRoute.value.params.slug
        this.prepareData()
    },
    methods: {
        async prepareData(){
            const $this = this
            await this.axios.get('/api/pages/' + $this.slug)
                .then((response) => {
                    $this.page.isLoading = false
                    $this.page.data = response.data

                    emitter.emit('set-title', response.data.status && response.data.data?.title)
                })
                .catch((e) => {
                    $this.page.isLoading = false
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        }
    },
}
</script>

<style scoped>

</style>
