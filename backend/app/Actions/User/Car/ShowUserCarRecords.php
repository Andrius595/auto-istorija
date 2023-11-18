<?php

namespace App\Actions\User\Car;

use App\Actions\Car\ShowCar;
use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUserCarRecords
{
    use AsAction;

    public function handle(Car $car)
    {
        $car->load('appointments.records');

        return $car->appointments->pluck('records')->collapse();
    }

    public function asController(ActionRequest $request, User $user, Car $car): JsonResponse
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

        return  $user->id === $car->owner_id;
    }
}
