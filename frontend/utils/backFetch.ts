import {type AsyncData, type UseFetchOptions} from "nuxt/app";
import {FetchError} from "ofetch";
import {AvailableRouterMethod, NitroFetchRequest} from "nitropack";
import {KeysOf, PickFrom} from "#app/composables/asyncData";

export default async <DataT>(
    route: string,
    options: UseFetchOptions<
        DataT extends void ? unknown : DataT,
        DataT extends void ? unknown : DataT,
        KeysOf<DataT extends void ? unknown : DataT>,
        null,
        NitroFetchRequest,
        DataT extends void ? "get" : AvailableRouterMethod<NitroFetchRequest>>
): Promise<AsyncData<PickFrom<DataT extends void ? unknown : DataT, KeysOf<DataT extends void ? unknown : DataT>>|null,FetchError|null>> =>
{
    const jwt = useJWT()

    let token = await jwt.getToken()

    if (!token) {
        await navigateTo('/login');
    }

    options.headers = {
        ...options.headers,
        Authorization: 'Bearer ' + token
    }

    return useFetch<DataT, FetchError>(useRuntimeConfig().public.apiURL + route, options)
}