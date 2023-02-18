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

    </vue-cookie-comply>
</template>

<script>
import CookieComplyButton from 'vue-cookie-comply/dist/vue-cookie-comply.es'
import tr from "../locales/tr";

export default {
    name: "Footer",
    components: {
        CookieComplyButton
    },
    data(){
        return {
            cookie: {
                headerTitle: 'Çerez Ayarları',
                preferencesLabel: 'Seçenekler',
                headerDescription: 'Deneme açıklama',
                acceptAllLabel: 'Tümünü Kabul Et',
                preferences: [
                    {
                        title: 'Temel İzinler',
                        description: 'Buraya açıklama yazılmalı',
                        items: [
                            {
                                label: 'Aktif',
                                value: 'deneme-deger',
                                isRequired: true
                            }
                        ],
                    },
                ]
            }
        }
    },
    methods: {
        onAccept() {
            console.log('Tümünü kabul etti')
        },
        onSavePreferences(preferences) {
            console.log('Seçilenleri kabul etti', preferences)
        },
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
