import { createI18n } from 'vue-i18n'
import tr from '@/src/locales/tr.js'

const messages = {
    tr,
}

const i18n = createI18n({
    legacy: false,
    locale: 'tr',
    globalInjection: true,
    messages,
})

export default i18n
