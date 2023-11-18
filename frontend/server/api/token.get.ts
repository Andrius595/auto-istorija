import { getToken } from '#auth'
export default eventHandler(async (event) => {
    const token = await getToken({ event })

    console.log('ttt', token)

    return token
})
