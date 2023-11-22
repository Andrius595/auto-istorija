import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { VDataTableServer } from "vuetify/components/VDataTable";

export default defineNuxtPlugin((app) => {
    const vuetify = createVuetify({
        components: {
            VDataTableServer
        }
    })
    app.vueApp.use(vuetify)
})