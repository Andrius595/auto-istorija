<?php

namespace App\Actions\User\Car;

use App\Actions\Car\DeleteCar;
use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserCar
{
    use AsAction;

    public function asController(ActionRequest $request, User $user, Car $car): JsonResponse
    {
        DeleteCar::run($car);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        $loggedInUser = $request->user();

        $user = $request->route('user');
        $car = $request->route('car');

        $carBelongsToUser = $user->id === $car->owner_id;

        if (!$carBelongsToUser) {
            return false;
        }

        $performingActionOnHisAccount = $loggedInUser->id === $user->id;

        return (
            $performingActionOnHisAccount &&
            $loggedInUser->hasPermissionTo(PermissionsConfig::CAN_DELETE_CAR)
            ) || $loggedInUser->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
