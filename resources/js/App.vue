<template>
    <Header />
    <component :is="layout">
        <router-view></router-view>
    </component>
<!--    <Footer />-->
</template>

<script>
import Header from "@/src/layout/Header.vue";
// import Footer from "@/src/layout/Footer.vue";
import Default from "@/src/layout/Default.vue";
import FullWidth from "@/src/layout/FullWidth.vue";

export default {
    name: "App",
    components: {
        Header,
        // Footer,
        Default,
        FullWidth
    },
    data() {
        return {
            layout: null,
        };
    },
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    },
    watch: {
        $route(to) {
            if (to.meta.layout !== undefined && to.meta.layout.length > 0) {
                this.layout = to.meta.layout;
            } else {
                this.layout = "Default";
            }
        },
    },
}
</script>

<style scoped>

</style>
