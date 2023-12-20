export type UserSession = {
    id: number,
    first_name: string,
    last_name: string,
    email: string,
    email_verified_at: string|null,
    roles: string[],
    service_id: number|null,
    permissions: string[],
    created_at: string|null,
    updated_at: string|null,
}
