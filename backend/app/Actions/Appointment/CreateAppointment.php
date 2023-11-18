<?php

namespace App\Actions\Appointment;

use App\Config\PermissionsConfig;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateAppointment
{
    use AsAction;

    public function handle(int $carId, int $serviceId): Appointment
    {
        return Appointment::create([
            'car_id' => $carId,
            'service_id' => $serviceId,
        ]);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $appointment = $this->handle($data['car_id'], $data['service_id']);

        return response()->json($appointment, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|int',
            'car_id' => 'required|int',
        ];
    }
}
