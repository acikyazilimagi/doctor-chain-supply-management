import * as validators from '@vuelidate/validators'
import i18n from "@/i18n";

const { createI18nMessage } = validators

const withI18nMessage = createI18nMessage({
	t: i18n.global.t.bind(i18n),
	messagePath: ({ $validator }) => {
        return `vuelidate.${$validator}`
    },
})

export const alpha = withI18nMessage(validators.alpha)
export const alphaNum = withI18nMessage(validators.alphaNum)
export const and = withI18nMessage(validators.and)
export const between = withI18nMessage(validators.between, {
	withArguments: true,
})
export const decimal = withI18nMessage(validators.decimal)
export const email = withI18nMessage(validators.email)
export const integer = withI18nMessage(validators.integer)
export const ipAddress = withI18nMessage(validators.ipAddress)
export const macAddress = withI18nMessage(validators.macAddress)
export const maxLength = withI18nMessage(validators.maxLength, {
	withArguments: true,
})
export const maxValue = withI18nMessage(validators.maxValue, {
	withArguments: true,
})
export const minLength = withI18nMessage(validators.minLength, {
	withArguments: true,
})
export const minValue = withI18nMessage(validators.minValue, {
	withArguments: true,
})
export const not = withI18nMessage(validators.not)
export const numeric = withI18nMessage(validators.numeric)
export const or = withI18nMessage(validators.or)
export const required = withI18nMessage(validators.required)
export const requiredIf = (prop) => withI18nMessage(validators.requiredIf(prop))
export const requiredUnless = (prop) =>
	withI18nMessage(validators.requiredUnless(prop))
export const sameAs = withI18nMessage(validators.sameAs, {
	withArguments: true,
})
export const url = withI18nMessage(validators.url)
