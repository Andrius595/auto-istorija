<?php

namespace App\Actions\Appointment\Records;

use App\Actions\Record\CreateRecord;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateAppointmentRecord
{
    use AsAction;

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();

        $record = CreateRecord::run($appointment, $data);

        return response()->json($record, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        return $user->hasPermissionTo(PermissionsConfig::CAN_CREATE_RECORD) ||
            $user->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }

    public function rules(): array
    {
        return [
            'current_mileage' => 'required|int',
            'mileage_type' => 'required|boolean',
            'description' => 'required|string',
        ];
    }
}
