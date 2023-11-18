<?php

namespace App\Actions\Service\Car;

use App\Actions\Car\ShowCar;
use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class ShowServiceCar
{
    use AsAction;

    public function asController(ActionRequest $request, Service $service, Car $car): JsonResponse
    {
        $appointment = $car->appointments
            ->where('service_id', $service->id)
            ->where('completed_at', null)
            ->first();

        if (!$appointment) {
            return response()->json(['message' => 'Car is not currently in service'], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(ShowCar::run($car));
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_VIEW_CAR)) {
            return false;
        }

        $service = $request->route('service');

        return  $user->service_id === $service->id || $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
