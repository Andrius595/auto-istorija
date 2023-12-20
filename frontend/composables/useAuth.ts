import {UserSession} from "~/types/userSession";
import {useJWT} from "~/composables/useJWT";

export const useAuth = () => {
    const _user = useState<UserSession>('user')
    const jwt = useJWT()

    const setUser = (user: UserSession) => {
        _user.value = user
    }

    const fetchUser = async () => {
        const { data } = await backFetch<UserSession>('/auth/user', {method: 'GET'})

        return data.value
    }

    const getUser = async (forceFetch = false): Promise<UserSession> => {
        if (forceFetch || _user.value === undefined) {
            const user = await fetchUser()
            if (user && 'id' in user) {
                setUser(user)
            }
        }

        return _user.value
    }

    const logout = async () => {
        await backFetch('/auth/logout', {method: 'POST'})

        jwt.setToken('')
        _user.value = undefined as unknown as UserSession
        await navigateTo('/login')
    }

    return { getUser, logout }
}