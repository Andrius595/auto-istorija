<?php

namespace App\Actions\Appointment\Records;

use App\Actions\Record\ListRecords;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Record;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListAppointmentRecords
{
    use AsAction;

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        $searchParams = $request->only([
            'description',
        ]);
        $searchParams['appointment_id'] = $appointment->id;

        return response()->json(ListRecords::run($request->get('perPage', 10), $searchParams));
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        if ($user->hasRole(PermissionsConfig::SYSTEM_ADMIN)) {
            return true;
        }

        $appointment = $request->route('appointment');

        $userIsServiceEmployee = $user->hasRole(PermissionsConfig::SERVICE_EMPLOYEE_ROLE);
        $userBelongsToService = $user->service_id === $appointment->service_id;

        return $userIsServiceEmployee && $userBelongsToService &&
            $user->hasPermissionTo(PermissionsConfig::CAN_LIST_APPOINTMENTS);
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required|int|min:1',
            'page' => 'required|int|min:1',
            'description' => 'nullable|string',
        ];
    }
}
