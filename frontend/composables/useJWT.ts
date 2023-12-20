import {jwtDecode, type JwtPayload} from "jwt-decode";
import {type JwtData} from "~/types/JWT";

export const useJWT = () => {
    const get = () => {
        return useCookie('token').value
    }

    const setToken = (token: string) => {
        const tokenCookie = useCookie('token')

        tokenCookie.value = token
    }

    const decodeToken = (token: string): JwtData => {
        return <JwtData>jwtDecode(token);
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

        setToken(data.value as string)

        return data.value
    }

    const getToken = async () => {
        let token = get()
        if (token) {
            if (isTokenExpired(token)) {
                if (isTokenRefreshable(token)) {
                    const refreshed = await refresh(token)
                    if (!refreshed) {
                        setToken('')
                        return null;
                    }
                } else {
                    setToken('')

                    return null;
                }
            }
        }

        return get()
    }

    const getTokenData = async (): Promise<JwtData|null> => {
        const token = await getToken()

        if (!token) {
            return null
        }

        return decodeToken(token)
    }

    return { getToken, setToken, isTokenExpired, isTokenRefreshable, refresh, decodeToken, getTokenData }
}