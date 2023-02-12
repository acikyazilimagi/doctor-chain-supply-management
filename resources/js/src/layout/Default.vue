<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-9">
                <router-view></router-view>
            </div>
            <div class="col-3">
                <ul class="list-group">
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Index' }">{{ $t('header.account_info') }}</router-link></li>
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Edit' }">{{ $t('header.update_account_info') }}</router-link></li>
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
