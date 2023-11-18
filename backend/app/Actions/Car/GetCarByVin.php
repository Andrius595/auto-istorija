<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCarByVin
{
    use AsAction;

    public function handle(string $vin): Car|null
    {
        return Car::where('vin', $vin)->first();
    }

    public function asController(ActionRequest $request, string $vin): JsonResponse
    {
        return response()->json($this->handle($vin));
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_VIEW_CAR)) {
            return false;
        }

        $userIsEmployee = $user->hasRole(PermissionsConfig::SERVICE_EMPLOYEE_ROLE); // TODO or service admin
        $userIsSystemAdmin = $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);

        return $userIsEmployee || $userIsSystemAdmin;
    }
}
