import {getRequestURL, H3Event} from "h3";
import {$fetch} from "ofetch";

export default defineEventHandler(async (event: H3Event) => {
    const options = await readBody(event)

    const t = getCookie(event, 'token')

    const url = getRequestURL(event)

    const path = url.pathname.replace('/api/backend/', '/')

    options.headers = {
        Accept: 'application/json',
        Authorization: 'Bearer ' + t
    }

    return await $fetch(useRuntimeConfig().public.apiURL + path, options)
})
