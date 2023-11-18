<?php

namespace App\Actions\User\Car;

use App\Actions\Car\CreateCar;
use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateUserCar
{
    use AsAction;

    public function asController(ActionRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        $car = CreateCar::run($user, $data);

        return response()->json($car, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request): bool
    {
        $loggedInUser = $request->user();
        $user = $request->route('user');

        $performingActionOnHisAccount = $loggedInUser->id === $user->id;

        return $performingActionOnHisAccount &&
            ($loggedInUser->hasPermissionTo(PermissionsConfig::CAN_CREATE_CAR) ||
            $loggedInUser->hasRole(PermissionsConfig::SYSTEM_ADMIN));
    }

    public function rules(): array
    {
        return [
            'make' => 'required|string',
            'model' => 'required|string',
            'year_of_manufacture' => 'required|date_format:Y|before_or_equal:now',
            'color' => 'required|string',
            'plate_no' => 'required|string',
            'vin' => 'required|string', // TODO additional validation
        ];
    }
}
