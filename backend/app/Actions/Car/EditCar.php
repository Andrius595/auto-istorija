<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditCar
{
    use AsAction;

    public function handle(Car|int $car, array $data): bool
    {
        if (is_int($car)) {
            $car = Car::findOrFail($car);
        }

        return $car->update($data);
    }

    public function asController(ActionRequest $request, Car $car): JsonResponse
    {
        $data = $request->validated();

        $this->handle($car, $data);

        return response()->json($car);
    }

    public function rules(): array
    {
        return [
            'make' => 'required|string',
            'model' => 'required|string',
            'year_of_manufacture' => 'required|date_format:Y|before_or_equal:now', // TODO not sure if 'now' is working...
            'color' => 'nullable|string',
            'plate_no' => 'nullable|string',
        ];
    }


    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_EDIT_CAR)) {
            return false;
        }

        $car = $request->route('car');
        $userIsOwner = $user->id === $car->owner_id;

        return $userIsOwner || $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
