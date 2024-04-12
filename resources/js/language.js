import { usePage } from "@inertiajs/vue3"

export default {
    install(app) {
        app.config.globalProperties.__ = function (key, replace = {}) {
            let translation = usePage().props.language[key] || key

            for (let [key, value] of Object.entries(replace)) {
                translation = translation.replace(`:${key}`, value)
            }

            return translation
        }

        app.provide('__', app.config.globalProperties.__)
    }
}