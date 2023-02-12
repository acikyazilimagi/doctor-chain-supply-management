<template>
    <nav class="navbar navbar-expand bg-light">
        <div class="container-fluid">
            <router-link class="navbar-brand" :to="{ name: 'Index' }">{{ $t('header.project_name') }}</router-link>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Recipes.All' }">{{ $t('header.all_recipes') }}</router-link>
                    </li>
                    <template v-if="user">
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'Recipes.Create' }">{{ $t('header.create_recipe') }}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'Recipes.Index' }">{{ $t('header.all_my_recipes') }}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'Account.Profile.Index' }">{{ $t('header.my_account') }}</router-link>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" @click="logout">{{ $t('general.logout') }}</a>
                        </li>
                    </template>
                    <template v-else>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'Auth.Register' }">{{ $t('general.register') }}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'Auth.Login' }">{{ $t('general.login') }}</router-link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import {mapActions} from "vuex";
export default {
    name: "Header",
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    },
    methods:{
        ...mapActions({
            signOut:"auth/logout"
        }),
        async logout(){
            await axios.post('/api/auth/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"Auth.Login"})
            })
        }
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
