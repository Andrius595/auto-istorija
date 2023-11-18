<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowCar
{
    use AsAction;

    public function handle(Car|int $car): Car
    {
        if (is_int($car)) {
            return Car::findOrFail($car);
        }

        return $car;
    }

    public function asController(ActionRequest $request, Car $car): JsonResponse
    {
        return response()->json($this->handle($car));
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_VIEW_CAR)) {
            return false;
        }

        $car = $request->route('car');
        $userIsOwner = $user->id === $car->owner_id;
        $userIsEmployee = $user->hasRole(PermissionsConfig::SERVICE_EMPLOYEE_ROLE); // TODO or service admin
        $carIsInEmployeesService = $user->service_id === $car->appointments()->where('completed_at', null)->first()?->service_id;

        return $userIsOwner || ($userIsEmployee && $carIsInEmployeesService) || $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
