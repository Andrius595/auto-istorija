<?php

namespace App\Actions\Appointment;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class DeleteAppointment
{
    use AsAction;

    public function handle(Appointment|int $appointment): bool
    {
        if (is_int($appointment)) {
            $appointment = Appointment::find($appointment);
        }

        return (bool)$appointment->delete();
    }

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        $this->handle($appointment);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
