<template>
    <div class="container-fluid">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <router-link class="d-flex align-items-center mb-2 mb-md-0 text-dark text-decoration-none" :to="{ name: 'Index' }">
                <img src="/assets/img/logo.png" style="height: 32px" :alt="$t('header.project_name')">
            </router-link>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><router-link class="nav-link px-2 link-dark" :to="{ name: 'Recipes.All' }">{{ $t('header.all_recipes') }}</router-link></li>
                <li><router-link class="nav-link px-2 link-dark" :to="{ name: 'LegalText' }">Aydınlatma Metni</router-link></li>

                <template v-if="getAuthenticated">
                    <li><router-link class="nav-link px-2 link-dark" :to="{ name: 'Account.Recipes.Create' }">{{ $t('header.create_recipe') }}</router-link></li>
                    <li><router-link class="nav-link px-2 link-dark" :to="{ name: 'Account.Recipes.Index' }">{{ $t('header.all_my_recipes') }}</router-link></li>
                    <li><router-link class="nav-link px-2 link-dark" :to="{ name: 'Account.Profile.Index' }">{{ $t('header.my_account') }}</router-link></li>
                </template>
            </ul>

            <div class="text-end">
                <template v-if="getAuthenticated">
                    <a href="#" class="btn btn-info" @click="logoutUser">{{ $t('general.logout') }}</a>
                </template>
                <template v-else>
                    <router-link class="btn btn-primary me-2" :to="{ name: 'Auth.Register' }">{{ $t('general.register') }}</router-link>
                    <router-link class="btn btn-primary" :to="{ name: 'Auth.Login' }">{{ $t('general.login') }}</router-link>
                </template>
            </div>
        </header>
    </div>
</template>

<script>
import {mapActions,mapGetters} from "vuex";
export default {
    name: "Header",
    computed : {
        ...mapGetters('auth',[
            'getUser',
            'getAuthenticated'
        ])
    },
    methods:{
        ...mapActions('auth', [
            'logoutUser'
        ]),
    }
}
</script>

<style scoped>
    .router-link-active,
    .router-link-exact-active {
        background-color: indianred;
        cursor: pointer;
    }
</style>
