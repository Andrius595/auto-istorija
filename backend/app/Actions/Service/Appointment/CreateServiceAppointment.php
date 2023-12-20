<?php

namespace App\Actions\Service\Appointment;

use App\Actions\Appointment\CreateAppointment;
use App\Config\PermissionsConfig;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateServiceAppointment
{
    use AsAction;

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        $appointmentData = [
            'car_id' => $request->get('car_id'),
            'service_id' => $service->id
        ];
        $carData = $request->validated(['make', 'model', 'vin']);

        $hasActiveAppointment = false;
        if ($appointmentData['car_id']) {
            $car = Car::findOrFail($appointmentData['car_id']);

            $hasActiveAppointment = $car->appointments()->whereNull('completed_at')->exists();
        }

        if ($hasActiveAppointment) {
            return response()->json([
                'message' => 'Car already has an active appointment',
            ], Response::HTTP_CONFLICT);
        }

        $appointment = CreateAppointment::run($appointmentData['car_id'], $appointmentData['service_id'], $carData);

        return response()->json($appointment, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_APPOINTMENT) &&
            ($request->user()->hasRole(PermissionsConfig::SERVICE_EMPLOYEE_ROLE) ||
                $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN));
    }

    public function rules(): array
    {
        return [
            'car_id' => 'nullable|exists:cars,id',
            'vin' => 'required|string',
            'make' => 'required_without:car_id|string',
            'model' => 'required_without:car_id|string',
        ];
    }
}
