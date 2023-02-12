<template>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>{{ $t('modules.account.referral.name') }}</td>
                <td>{{ user.name }}</td>
            </tr>
            <tr>
                <td>{{ $t('modules.account.referral.email') }}</td>
                <td>{{ user.email }}</td>
            </tr>
            <tr>
                <td>{{ $t('modules.account.referral.specialty') }}</td>
                <td>{{ user.specialty?.name }}</td>
            </tr>
        </tbody>
    </table>
    <div class="accordion mt-3">
        <div v-for="referral_link in referral_links" class="accordion-item">
            <h2 class="accordion-header" :id="'referral_link_' + referral_link.id">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#referral_link_container_' + referral_link.id" aria-expanded="false" :aria-controls="'referral_link_container_' + referral_link.id">
<!--                    <span class="badge bg-warning me-2">{{ referral_link.count }}</span>-->
                    <span>{{ referral_link.code }}</span>
                </button>
            </h2>
            <div :id="'referral_link_container_' + referral_link.id" class="accordion-collapse collapse" :aria-labelledby="'referral_link_container_' + referral_link.id" :data-bs-parent="'#referral_link_' + referral_link.id">
                <div class="accordion-body">
                    <h4 class="text-danger fw-bold my-2 d-block">{{ generateReferralLink(referral_link.code) }}</h4>

                    <h5 class="text-success ms-auto fw-bold">{{ $t('modules.account.referral.referenced_persons') }}</h5>

                    <ul class="list-group">
                        <li class="list-group-item" v-for="user in referral_link.users" :key="'user_' + user.id">
                            <span class="d-flex">{{ user.email }} - {{ user.name }}</span>
                            <span v-if="user.verified" class="text-success">{{ $t('general.verified') }}</span>
                            <button v-else class="btn btn-ssm btn-info ms-auto d-flex" @click.prevent="verifyFriend(user)">{{ $t('modules.account.referral.confirm_my_friend') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Account.Profile",
    computed : {
        user() {
            return this.$store.getters['auth/user']
        }
    },
    created() {
        this.prepareReferralLinks()
    },
    data(){
        return {
            referral_links: []
        }
    },
    methods: {
        async prepareReferralLinks(){
            await axios
                .get('/api/account/referral-links')
                .then((response)=>{
                    this.referral_links = response.data.data
                }).catch(({response})=>{
                    if(response.status===422){
                        this.validationErrors = response.data.errors
                    }else{
                        this.validationErrors = {}
                        alert(response.data.message)
                    }
                })
        },
        async verifyFriend(user){
            await axios
                .post('/api/account/referral-links/verify-friend', {
                    id: user.id
                })
                .then((response)=>{
                    if (response.status) {
                        this.prepareReferralLinks()
                    }
                    this.$swal.fire(response.data.message.title, response.data.message.body, response.data.message.type)
                }).catch((e)=>{
                    this.$swal.fire('Hata', e.response.data.message, 'error')
                })
        },
        generateReferralLink(code){
            return new URL(this.$router.resolve({ name: 'Auth.Register' }).href, window.location.origin).href + '?referral_code=' + code;

        }
    }
}
</script>

<style scoped>

</style>
