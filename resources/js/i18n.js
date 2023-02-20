import { createI18n } from 'vue-i18n'
import tr from '@/src/locales/tr.js'
import en from '@/src/locales/en.js'

const messages = {
    tr,
    en,
}

const i18n = createI18n({
    legacy: false,
    locale: 'tr',
    globalInjection: true,
    messages,
})

export default i18n
