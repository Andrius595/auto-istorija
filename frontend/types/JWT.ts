import type {JwtPayload} from "jwt-decode";

export type JwtData =  JwtPayload &{
    roles: string[]
}
