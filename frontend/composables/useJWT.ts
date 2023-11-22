import {jwtDecode, type JwtPayload} from "jwt-decode";
import backFetch from "~/utils/backFetch";

export const useJWT = () => {
    const getToken = () => {
        return useCookie('token').value
    }

    const setToken = (token: string) => {
        const tokenCookie = useCookie('token')

        tokenCookie.value = token
    }

    const decodeToken = (token: string): JwtPayload => {
        return jwtDecode(token);
    }

    const isTokenExpired = (token: string) => {
        const tokenObject = decodeToken(token)

        return Date.now() >= (tokenObject.exp as number) * 1000;
    }

    const isTokenRefreshable = (token: string) => {
        const tokenObject = decodeToken(token)

        return Date.now() < (tokenObject.iat as number) * 1000 + (useRuntimeConfig().public.jwtRefreshTTL as number) * 1000;
    }

    const refresh = async (token: string) => {
        const { data } = await useFetch('api/auth/refresh', {
            method: 'POST',
            body: { token }
        })

        console.log('refresh JWT', data, data.value)
        setToken(data.value as string)

        return data.value
    }

    return { getToken, setToken, isTokenExpired, isTokenRefreshable, refresh, decodeToken }
}