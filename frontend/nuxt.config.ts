// https://nuxt.com/docs/api/configuration/nuxt-config
import vuetify, { transformAssetUrls} from "vite-plugin-vuetify";
export default defineNuxtConfig({
  devtools: {enabled: true},
  ssr: false,
  build: {
    transpile: ['vuetify', 'vue-sonner'],
  },
  modules: [
    '@nuxtjs/google-fonts',
    (_options, nuxt) => {
      nuxt.hooks.hook('vite:extendConfig', (config) => {
        // @ts-expect-error
        config.plugins.push(vuetify({autoImport: true}))
      })
    },
  ],
  googleFonts: {
    families: {
      Afacad: true,
    }
  },
  vite: {
    vue: {
      template: {
        transformAssetUrls,
      },
    },
  },
  runtimeConfig: {
    jwtSecret: process.env.JWT_SECRET,
    public: {
      apiURL: process.env.BACKEND_API_URL || 'http://localhost/api',
      jwtTTL: ((process.env.JWT_TTL as number|undefined) || 60) * 60,
      jwtRefreshTTL: ((process.env.JWT_REFRESH_TTL as number|undefined) || 60*24*7) * 60,
    }
  }
})
