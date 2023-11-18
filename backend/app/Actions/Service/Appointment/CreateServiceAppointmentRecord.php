<?php

namespace App\Actions\Service\Appointment;

use App\Actions\Appointment\CreateAppointment;
use App\Actions\Record\CreateRecord;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateServiceAppointmentRecord
{
    use AsAction;

    public function asController(ActionRequest $request, Service $service, Appointment $appointment): JsonResponse
    {
        if ($appointment->completed_at !== null) {
            return response()->json(['message' => 'Service appointment already completed'], Response::HTTP_BAD_REQUEST);
        }

        $data = $request->validated();

        $record = CreateRecord::run($appointment, $data);


        return response()->json($record, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_APPOINTMENT);
    }

    public function rules(): array
    {
        return [
            'current_mileage' => 'required|integer',
            'mileage_type' => ['required', Rule::in([Car::MILEAGE_TYPE_KILOMETERS, Car::MILEAGE_TYPE_MILES])],
            'description' => 'required|string',
        ];
    }
}
