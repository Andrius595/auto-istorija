export type Car = {
    id: number;
    owner_id: number|null;
    make: string;
    model: string;
    vin: string;
    year_of_manufacture: number|null;
    created_at: string|null;
    updated_at: string|null;
}