import {type AsyncData, type UseFetchOptions} from "nuxt/app";
import type {NavigationFailure, RouteLocationRaw} from "vue-router";

export default async (route: string, options: UseFetchOptions<any>): Promise<AsyncData<any, any>|Promise<void | NavigationFailure | false> | false | void | RouteLocationRaw> => {
    const jwt = useJWT()

    let token = useCookie('token').value
    console.log('token value', token)
    if (token) {
        const t = jwt.decodeToken(token)
        console.log('decoded', t)
        console.log('token present', token)
        if (jwt.isTokenExpired(token)) {
            console.log('token expired')
            if (jwt.isTokenRefreshable(token)) {
                console.log('token refreshable')
                const refreshed = await jwt.refresh(token)
                console.log('refreshed', refreshed)
                if (!refreshed) {
                    jwt.setToken('')
                    return navigateTo('/login');
                }
            } else {
                console.log('token not refreshable')
                jwt.setToken('')

                return navigateTo('/login');
            }
        }
        token = useCookie('token').value
    }


    options.headers = {
        ...options.headers,
        Authorization: 'Bearer ' + token
    }


    const response = useFetch(useRuntimeConfig().public.apiURL + route, options)

    // console.log('response', response)

    return response
}