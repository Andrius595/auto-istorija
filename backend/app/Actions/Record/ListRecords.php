<?php

namespace App\Actions\Record;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Record;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListRecords
{
    use AsAction;

    public function handle(int $perPage, array $searchParams = [], array $relations = []): LengthAwarePaginator
    {
        $query = Record::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($searchParams as $key => $searchParam) {
            match ($key) {
                'appointment_id' => $query->where('appointment_id', $searchParam),
                'mileage_type' => $query->where('mileage_type', $searchParam),
                default => $query->where($key, 'like', "%$searchParam%"),
            };
        }

        return $query->paginate($perPage);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $searchParams = $request->only([
            'car_id',
            'service_id',
            'description',
        ]);

        return response()->json($this->handle(10, $searchParams));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_LIST_APPOINTMENTS);
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required|int|min:1',
            'page' => 'required|int|min:1',
            'car_id' => 'nullable|exists:cars,id',
            'service_id' => 'nullable|exists:services,id',
            'description' => 'nullable|string',
        ];
    }
}
