<?php

namespace App\Actions\Appointment;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListAppointments
{
    use AsAction;

    public function handle(int $perPage, int $carId, string $description = null): LengthAwarePaginator
    {
        $query = Appointment::query()->where('car_id', $carId);

        if ($description) {
            $query->where('description', 'like', "%$description%");
        }

        return $query->paginate($perPage);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        return response()->json($this->handle(10, $request->car_id, $request->description));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required|int|min:1',
            'page' => 'required|int|min:1',
            'car_id' => 'required|exists:cars,id',
            'description' => 'nullable|string',
        ];
    }
}
