<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateCar
{
    use AsAction;

    public function handle(User $owner, array $data): Car
    {
        return $owner->cars()->create($data);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $owner = $user;
        if (isset($data['owner_id']) && $user->hasRole(PermissionsConfig::SYSTEM_ADMIN)) {
            $owner = User::findOrFail($data['owner_id']);
        }

        $car = $this->handle($owner, $data);

        return response()->json($car, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_CAR);
    }

    public function rules(): array
    {
        return [
            'make' => 'required|string',
            'model' => 'required|string',
            'year_of_manufacture' => 'required|date_format:Y|before_or_equal:now', // TODO not sure if 'now' is working...
            'vin' => 'required|string', // TODO additional validation
            'owner_id' => 'sometimes|exists:users,id'
        ];
    }
}
