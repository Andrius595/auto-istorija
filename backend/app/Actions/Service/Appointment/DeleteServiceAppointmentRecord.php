<?php

namespace App\Actions\Service\Appointment;

use App\Actions\Record\DeleteRecord;
use App\Actions\Record\EditRecord;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Record;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class DeleteServiceAppointmentRecord
{
    use AsAction;

    public function asController(ActionRequest $request, Service $service, Appointment $appointment, Record $record): JsonResponse
    {
        if ($appointment->completed_at !== null) {
            return response()->json(['message' => 'Service appointment already completed'], Response::HTTP_BAD_REQUEST);
        }

        if ($record->appointment_id !== $appointment->id) {
            return response()->json(['message' => 'Record does not belong to this appointment'], Response::HTTP_BAD_REQUEST);
        }

        DeleteRecord::run($record);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();
        $service = $request->route('service');

        $userIsEmployee = $user->service_id === $service->id;
        $userIsAdmin = $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);

        return $user->hasPermissionTo(PermissionsConfig::CAN_DELETE_RECORD) && ($userIsEmployee || $userIsAdmin);
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
