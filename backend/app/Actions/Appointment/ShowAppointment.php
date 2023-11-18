<?php

namespace App\Actions\Appointment;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowAppointment
{
    use AsAction;

    public function handle(Appointment|int $appointment): Appointment
    {
        if (is_int($appointment)) {
            return Appointment::findOrFail($appointment);
        }

        return $appointment;
    }

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        return response()->json($this->handle($appointment));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
