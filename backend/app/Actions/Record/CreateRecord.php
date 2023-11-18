<?php

namespace App\Actions\Record;

use App\Models\Appointment;
use App\Models\Car;
use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use PermissionsConfig;
use Symfony\Component\HttpFoundation\Response;

class CreateRecord
{
    use AsAction;

    public function handle(Appointment $appointment, array $data): Record
    {
        return $appointment->records()->create($data);
    }

    public function asController(ActionRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();

        $record = $this->handle($appointment, $data);

        return response()->json($record, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_RECORD);
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|int',
            'car_id' => 'required|int',
        ];
    }
}
