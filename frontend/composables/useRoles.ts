import {Roles} from "~/enums/roles";
import {useJWT} from "~/composables/useJWT";
import type {JwtData} from "~/types/JWT";
import {asyncComputed} from "@vueuse/core/index";

export const useRoles = () => {
    const jwt = useJWT()

    const hasClientRole = async (): Promise<boolean> => {
        const data: JwtData|null = await jwt.getTokenData()

        if (!data) {
            return false
        }

        return data.roles.includes(Roles.CLIENT)
    }

    const hasServiceEmployeeRole = async (): Promise<boolean> => {
        const data: JwtData|null = await jwt.getTokenData()

        if (!data) {
            return false
        }

        return data.roles.includes(Roles.SERVICE_EMPLOYEE)
    }

    const hasSystemAdminRole = async (): Promise<boolean> => {
        const data: JwtData|null = await jwt.getTokenData()

        if (!data) {
            return false
        }

        return data.roles.includes(Roles.SYSTEM_ADMIN)
    }

    const isClient = asyncComputed(async () => {
        return await hasClientRole()
    })

    const isServiceEmployee = asyncComputed(async () => {
        return await hasServiceEmployeeRole()
    })

    const isSystemAdmin = asyncComputed(async () => {
        return await hasSystemAdminRole()
    })

    return { isClient, isServiceEmployee, isSystemAdmin }
}