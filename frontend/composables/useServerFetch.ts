import {NitroFetchOptions, NitroFetchRequest} from "nitropack";
import {UserSession} from "@/types/userSession";
import {CommonUseAuthReturn} from "@sidebase/nuxt-auth/dist/runtime/types";

export const useServerFetch = async (path: string, options: NitroFetchOptions<NitroFetchRequest>): Promise<unknown> => {
    const {data}: Partial<CommonUseAuthReturn<any, any, any, UserSession>> = useAuth()


    const token = data.value.user.access_token

    options.headers = {
        ...options.headers,
        'Authorization': 'Bearer ' + token
    }

    if (!path.startsWith('/')) {
        path = '/' + path
    }


    return await $fetch('http://localhost/api' + path, options)
}