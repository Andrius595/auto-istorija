import {H3Event} from "h3";
import { $fetch } from "ofetch";

export default defineEventHandler(async (event: H3Event) => {
    const body = await readBody(event)

    const response = await $fetch(useRuntimeConfig().public.apiURL + '/auth/refresh', {
        method: 'POST',
        body: body,
        headers: {
            Accept: 'application/json',
            Authorization: 'Bearer ' + getCookie(event, 'token')
        }
    })


    return response.token || ''
})