import { toast } from 'vuetify-sonner'
export function successToast(message: string) {
    return toast(message, {
        duration: Number.POSITIVE_INFINITY,
        action: {
            label: 'Close',
        },
        cardProps: {
            color: 'success',
        },
    })
}

export function errorToast(message: string) {
    return toast(message, {
        duration: Number.POSITIVE_INFINITY,
        action: {
            label: 'Close',
        },
        cardProps: {
            color: 'error',
        },
    })
}