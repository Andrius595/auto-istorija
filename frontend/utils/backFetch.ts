import {AsyncData} from "nuxt/app";
export default (route: string, options: object): AsyncData<any, any> => {
    return useFetch('/api/backend'+route, {
        method: 'POST',
        body: options,
    })
}