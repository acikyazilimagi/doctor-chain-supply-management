<template>
    <template v-if="user.isLoading">Kullanıcı verisi alınıyor..</template>
    <template v-else>
        <template v-if="user.data.status === false">
            <div class="alert alert-danger">{{ user.data.message.body }}</div>
        </template>
        <template v-else>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>{{ $t('modules.account.referral.full_name') }}</td>
                        <td>{{ user.data.data.full_name }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('modules.account.referral.email') }}</td>
                        <td>{{ user.data.data.email }}</td>
                    </tr>
                    <tr>
                        <td>{{ $t('modules.account.referral.specialty') }}</td>
                        <td>{{ user.data.data.specialty?.name }}</td>
                    </tr>
                </tbody>
            </table>
        </template>
    </template>

    <template v-if="referral_links.isLoading">Referans linki verisi alınıyor..</template>
    <template v-else>
        <template v-if="referral_links.data.status === false">
            <div class="alert alert-danger">{{ referral_links.data.message.body }}</div>
        </template>
        <template v-else>
            <div class="accordion mt-3">
                <div v-for="referral_link in referral_links.data.data" class="accordion-item">
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
                                    <span class="d-flex">{{ user.email }} - {{ user.full_name }}</span>
                                    <span v-if="user.verified" class="text-success">{{ $t('general.verified') }}</span>
                                    <button v-else class="btn btn-ssm btn-info ms-auto d-flex" @click.prevent="verifyFriend(user)">{{ $t('modules.account.referral.confirm_my_friend') }}</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </template>
</template>

<script>
import emitter from '@/EventBus.js'

export default {
    name: "Account.Profile",
    created() {
        this.prepareUserData()
        this.prepareReferralLinks()
        emitter.emit('set-title', 'Hesabım')
    },
    data(){
        return {
            user: {
                isLoading: true,
                data: null,
            },
            referral_links: {
                isLoading: true,
                data: null,
            },
        }
    },
    methods: {
        async prepareUserData(){
            const $this = this
            $this.user.isLoading = true
            await axios
                .get('/api/account/profile')
                .then((response) => {
                    $this.user.isLoading = false
                    $this.user.data = response.data
                })
                .catch((e) => {
                    $this.user.isLoading = false
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        },
        async prepareReferralLinks(){
            const $this = this
            $this.referral_links.isLoading = true
            await axios
                .get('/api/account/referral-links')
                .then((response) => {
                    $this.referral_links.isLoading = false
                    $this.referral_links.data = response.data
                })
                .catch((e) => {
                    $this.referral_links.isLoading = false
                    this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                })
        },
        async verifyFriend(user){
            const data = {
                id: user.id
            }

            await axios
                .post('/api/account/referral-links/verify-friend', data)
                .then((response)=>{
                    if (response.data.status) {
                        this.prepareReferralLinks()
                    }
                    this.$swal.fire(response.data.message.title, response.data.message.body, response.data.message.type)
                })
                .catch((e) => {
                    if (e.response.status === 422) {
                        this.validation.errors = e.response.data.errors
                        this.validation.message = e.response.data.message
                    } else {
                        this.$swal.fire(e.response.data.message.title, e.response.data.message.body, e.response.data.message.type)
                    }
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
