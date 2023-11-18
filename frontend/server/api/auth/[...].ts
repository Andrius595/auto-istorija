import CredentialsProvider from 'next-auth/providers/credentials'
import { NuxtAuthHandler } from '#auth'
export default NuxtAuthHandler({
    // A secret string you define, to ensure correct encryption
    secret: 'your-secret-here',
    pages: {
        // Change the default behavior to use `/login` as the path for the sign-in page
        signIn: '/login'
    },
    providers: [
        // @ts-expect-error You need to use .default here for it to work during SSR. May be fixed via Vite at some point
        CredentialsProvider.default({
            // The name to display on the sign in form (e.g. 'Sign in with...')
            name: 'Credentials',
            // The credentials is used to generate a suitable form on the sign in page.
            // You can specify whatever fields you are expecting to be submitted.
            // e.g. domain, username, password, 2FA token, etc.
            // You can pass any HTML attribute to the <input> tag through the object.
            credentials: {
                email: { label: 'Email', type: 'email' },
                password: { label: 'Password', type: 'password' }
            },
            async authorize (credentials: any, req: any) {
                // TODO implement refresh token
                const tokenResponse: any = await fetch('http://localhost/api/auth/login', {
                    method: 'POST',
                    body: JSON.stringify(credentials),
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
                })
                const token = await tokenResponse.json()

                if (!tokenResponse.ok) {
                    throw new Error('Incorrect credentials')
                }

                // TODO this might need to be transferred to jwt callback
                const userResponse: any = await fetch('http://localhost/api/auth/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token.token}
                })
                const user = await userResponse.json()

                if (!userResponse.ok) {
                    throw new Error('Unable to fetch user')
                }

                user.access_token = token.token

                return user
            }
        })
    ],
    callbacks: {
        async jwt({ token, user, account, profile, isNewUser }: any) {

            if (account !== undefined) {
                // token.first_name = user.first_name
                // token.last_name = user.last_name
                // token.email = user.email
                // token.id = user.id
                // token.roles = user.roles
                // token.permissions = user.permissions
                // token.service_id = user.service_id
                token.user = user
            }

            return token
        },
        async session({ session, token }: any) {
            session.user = token.user

            return session
        },
    }
})