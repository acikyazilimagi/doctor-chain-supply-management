<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-9">
                <router-view></router-view>
            </div>
            <div class="col-3">
                <ul class="list-group">
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Index' }">Hesap Bilgilerim</router-link></li>
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Edit' }">Bilgilerimi Güncelle</router-link></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Default"
}
</script>

<style scoped>

</style>
