<?php

namespace App\Actions\User\Car;

use App\Actions\Appointment\EditAppointment;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class ConfirmUserCarAppointment
{
    use AsAction;

    public function asController(ActionRequest $request, User $user, Car $car, Appointment $appointment): JsonResponse
    {
        if ($appointment->confirmed_at !== null) {
            return response()->json(['message' => 'Car appointment already confirmed'], Response::HTTP_BAD_REQUEST);
        }

        EditAppointment::run($appointment, ['confirmed_at' => now()]);

        return response()->json(['message' => 'Car appointment confirmed successfully.'], Response::HTTP_OK);
    }

    public function authorize(ActionRequest $request): bool
    {
        $loggedInUser = $request->user();
        $user = $request->route('user');
        $car = $request->route('car');
        $appointment = $request->route('appointment');

        $carBelongsToUser = $user->id === $car->owner_id;
        $carBelongsToAppointment = $car->id === $appointment->car_id;

        if (!$carBelongsToUser || !$carBelongsToAppointment) {
            return false;
        }

        $performingActionOnHisAccount = $loggedInUser->id === $user->id;

        return $performingActionOnHisAccount || $loggedInUser->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
