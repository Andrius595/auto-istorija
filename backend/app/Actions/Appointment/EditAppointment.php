<?php

namespace App\Actions\Appointment;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditAppointment
{
    use AsAction;

    public function handle(Appointment|int $appointment, array $data): bool
    {
        if (is_int($appointment)) {
            $appointment = Appointment::findOrFail($appointment);
        }

        return $appointment->update($data);
    }

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();

        $this->handle($appointment, $data);

        return response()->json($appointment);
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable|string', // TODO handle nullable field
        ];
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }
}
