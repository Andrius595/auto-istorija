<?php

namespace App\Actions\User\Car;

use App\Actions\Car\EditCar;
use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditUserCar
{
    use AsAction;

    public function asController(ActionRequest $request, User $user, Car $car): JsonResponse
    {
        $data = $request->validated();

        EditCar::run($car, $data);

        return response()->json($car);
    }

    public function rules(): array
    {
        return [
            'make' => 'required|string',
            'model' => 'required|string',
            'year_of_manufacture' => 'required|date_format:Y|before_or_equal:now',
        ];
    }


    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if (!$user->hasPermissionTo(PermissionsConfig::CAN_EDIT_CAR)) {
            return false;
        }

        $car = $request->route('car');
        return $user->id === $car->owner_id;
    }
}
