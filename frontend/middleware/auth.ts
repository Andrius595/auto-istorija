import {useJWT} from "~/composables/useJWT";

export default defineNuxtRouteMiddleware(async (to, from) => {
    const jwt = useJWT()

    let token = ''
    // skip middleware on server
    if (process.server) {
    //     token = useCookie('token').value as string
    }
    // // skip middleware on client side entirely
    if (process.client) {
    //     const event = useRequestEvent()
    //     token = jwt.getToken(event) as string
    }


    // if (!jwt.isTokenValid(token)) {
    //     console.log('invalid')
    //     return navigateTo('/login');
    // }
    //
    // if (jwt.isTokenExpired(token)) {
    //     console.log('expired')
    //
    //     if (jwt.isTokenRefreshable(token)) {
    //         // refresh token
    //     } else {
    //         console.log('notRefreshable')
    //
    //         return navigateTo('/login');
    //     }
    // }
    // or only skip middleware on initial client load
    const nuxtApp = useNuxtApp()
    if (process.client && nuxtApp.isHydrating && nuxtApp.payload.serverRendered) {
    }
})