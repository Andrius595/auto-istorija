<?php

namespace App\Actions\Service\Appointment;

use App\Actions\Appointment\CreateAppointment;
use App\Actions\Appointment\EditAppointment;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CompleteServiceAppointment
{
    use AsAction;

    public function asController(ActionRequest $request, Service $service, Appointment $appointment): JsonResponse
    {
        if ($appointment->completed_at !== null) {
            return response()->json(['message' => 'Service appointment already completed'], Response::HTTP_BAD_REQUEST);
        }

        EditAppointment::run($appointment, ['completed_at' => now()]);

        return response()->json(['message' => 'Service appointment completed successfully!'], Response::HTTP_OK);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_APPOINTMENT);
    }

    public function rules(): array
    {
        return [
            'car_id' => 'nullable|exists:cars,id',
            'make' => 'required_without:car_id|string',
            'model' => 'required_without:car_id|string',
            'vin' => 'required_without:car_id|string',
        ];
    }
}
