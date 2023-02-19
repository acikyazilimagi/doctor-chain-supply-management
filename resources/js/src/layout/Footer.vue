<template>
<!--    <div class="border-top">-->
<!--        <div class="container">-->

<!--        </div>-->
<!--    </div>-->
    <vue-cookie-comply
        :headerTitle="cookie.headerTitle"
        :preferencesLabel="cookie.preferencesLabel"
        :headerDescription="cookie.headerDescription"
        :preferences="cookie.preferences"
        :acceptAllLabel="cookie.acceptAllLabel"
        @on-accept-all-cookies="onAccept"
        @on-save-cookie-preferences="onSavePreferences"
    >
        <template v-slot:modal-header>
            <h3>Çerez Ayarları</h3>
        </template>

        <template v-slot:modal-body>
            <div>
                <h4>Çerez Politikası</h4>
                <p>Biz, Şirket Adı, olarak güvenliğinize önem veriyor ve bu Çerez Politikası ile siz sevgili ziyaretçilerimizi, web sitemizde hangi çerezleri, hangi amaçla kullandığımız ve çerez ayarlarınızı nasıl değiştireceğiniz konularında kısaca bilgilendirmeyi hedefliyoruz.</p>
                <p>Sizlere daha iyi hizmet verebilmek adına, çerezler vasıtasıyla, ne tür kişisel verilerinizin hangi amaçla toplandığı ve nasıl işlendiği konularında, kısaca bilgi sahibi olmak için lütfen bu Çerez Politikasını okuyunuz. Daha fazla bilgi için Gizlilik Politikamıza göz atabilir ya da bizlerle çekinmeden iletişime geçebilirsiniz.</p>
                <h4>Çerez Nedir?</h4>
                <p>Çerezler, kullanıcıların web sitelerini daha verimli bir şekilde kullanabilmeleri adına, cihazlarına kaydedilen küçük dosyacıklardır. Çerezler vasıtasıyla kullanıcıların bilgilerinin işleniyor olması sebebiyle, 6698 sayılı Kişisel Verilerin Korunması Kanunu gereğince, kullanıcıların bilgilendirilmeleri ve onaylarının alınması gerekmektedir.</p>
                <p>Bizler de siz sevgili ziyaretçilerimizin, web sitemizden en verimli şekilde yararlanabilmelerini ve siz sevgili ziyaretçilerimizin kullanıcı deneyimlerinin geliştirilebilmesini sağlamak adına, çeşitli çerezler kullanmaktayız.</p>
            </div>

            <div v-for="(preference, index) in cookie.preferences" :key="index">
                    <h2>{{ preference.title }}</h2>
                    <p>{{ preference.description }}</p>
                    <div
                        v-for="item in preference.items"
                        :key="item.value"
                        class="cookie-comply__modal-switches"
                    >
                        <h3>{{ item.label }}</h3>
<!--                        TODO : Switch sorunu giderilmeli-->
                        <cookie-comply-switch
                            :value="item.value"
                            :is-required="item.isRequired"
                            :is-default-enable="item.isEnable"
                        />
                    </div>
            </div>
        </template>

    </vue-cookie-comply>
</template>

<script>
export default {
    name: "Footer",
    data(){
        return {
            cookie: {
                headerTitle: 'Çerez Ayarları',
                preferencesLabel: 'Seçenekler',
                headerDescription: 'Detaylı bilgilendirme için lütfen tıklayınız',
                acceptAllLabel: 'Tümünü Kabul Et',
                preferences: [
                    {
                        title: '1. Zorunlu Çerezler',
                        description: 'Zorunlu çerezler, web sitesine ilişkin temel işlevleri etkinleştirerek web sitesinin kullanılabilir hale gelmesini sağlayan çerezlerdir. Web sitesi bu çerezler olmadan düzgün çalışmaz.',
                        items: [
                            {
                                label: 'CSRF-TOKEN',
                                value: 'CSRF-TOKEN',
                                isRequired: true
                            },
                            {
                                label: 'medikal_ihtiyac_session',
                                value: 'medikal_ihtiyac_session',
                                isRequired: true
                            }
                        ],
                    },
                ]
            }
        }
    },
    methods: {
        onAccept() {},
        onSavePreferences() {},
    }
}
</script>

<style>
.cookie-comply__back-arrow {
    top: 25px;
}
.cookie-comply__modal-header h3 {
    padding-left: 20px;
}
</style>
