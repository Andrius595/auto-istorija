<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class DeleteCar
{
    use AsAction;

    public function handle(Car|int $car): bool
    {
        if (is_int($car)) {
            $car = Car::findOrFail($car);
        }

        return (bool)$car->delete();
    }

    public function asController(ActionRequest $request, Car $car): JsonResponse
    {
        $this->handle($car);

        return response()->json(null,Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_DELETE_CAR)) {
            return false;
        }

        $car = $request->route('car');
        $userIsOwner = $user->id === $car->owner_id;

        return $userIsOwner || $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
