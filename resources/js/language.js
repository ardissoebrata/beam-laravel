import { usePage } from "@inertiajs/vue3"
import lang_id from '../../lang/id.json'

export default {
    install(app) {
        app.config.globalProperties.language = {
            'id': lang_id
        }
        app.config.globalProperties.__ = function (key, replace = {}) {
            let pageLocale = usePage().props.locale
            if (!pageLocale) {
                pageLocale = 'id'
            }
            if (!app.config.globalProperties.language[pageLocale]) {
                return key
            }
            const language = app.config.globalProperties.language[pageLocale];
            let translation = language[key] || key

            for (let [key, value] of Object.entries(replace)) {
                translation = translation.replace(`:${key}`, value)
            }

            return translation
        }

        app.provide('__', app.config.globalProperties.__)
    }
}
