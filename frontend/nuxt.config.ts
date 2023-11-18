// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  // ssr: true,
  modules: [
    '@invictus.codes/nuxt-vuetify',
    '@sidebase/nuxt-auth'
  ],
  auth: {
    // baseURL: 'http://localhost/api',
    // provider: {
    //   type: 'local',
    //   endpoints: {
    //     signIn: { path: '/auth/login', method: 'post' },
    //     signOut: { path: '/auth/logout', method: 'post' },
    //     signUp: { path: '/auth/register', method: 'post' },
    //     getSession: { path: '/auth/user', method: 'get' }
    //   },
    //   sessionDataType: { id: 'number', email: 'string', first_name: 'string', last_name: 'string', roles: 'string[]', service_id: 'number|null', permissions: 'string[]', created_at: 'string|null', updated_at: 'string|null' },
    // },
    globalAppMiddleware: {
      isEnabled: true
    }
  }
})
