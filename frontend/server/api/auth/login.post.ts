import {H3Event} from "h3";
import { $fetch } from "ofetch";

export default defineEventHandler(async (event: H3Event) => {
    const body = await readBody(event)

    const response = await $fetch(useRuntimeConfig().public.apiURL + '/auth/login', {
        method: 'POST',
        body: body,
        headers: {
            Accept: 'application/json',
        }
    })

    setCookie(event, 'token', response.token)

    return response
})