<template>
    <div class="container mt-3">
        <div class="row flex-md-row-reverse">
            <div class="col-12 col-md-3 mb-3">
                <ul class="list-group">
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Index' }">{{ $t('header.account_info') }}</router-link></li>
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.Edit' }">{{ $t('header.update_account_info') }}</router-link></li>
                    <li class="list-group-item"><router-link :to="{ name: 'Account.Profile.ChangePassword' }">{{ $t('header.change_password') }}</router-link></li>
                </ul>
            </div>
            <div class="col-12 col-md-9">
                <router-view></router-view>
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
