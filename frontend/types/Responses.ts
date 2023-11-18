import { Car } from "~/types/Car";

export type EmptyResponse = {}

export type GetCarByVinResponse = Car | EmptyResponse

export type PaginatedResponse<DataT> = {
    current_page: number,
    data: DataT[],
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: {
        url: string,
        label: string,
        active: boolean
    }[],
    next_page_url: string,
    path: string,
    per_page: number,
    prev_page_url: string,
    to: number,
    total: number
}