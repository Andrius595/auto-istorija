<?php

namespace App\Actions\Appointment;

use App\Actions\Car\CreateCar;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateAppointment
{
    use AsAction;

    public function handle(int|null $carId, int $serviceId, $carData = []): Appointment
    {
        if (!$carId) {
            $car = CreateCar::run($carData);
            $carId = $car->id;
        }

        return Appointment::create([
            'car_id' => $carId,
            'service_id' => $serviceId,
        ]);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $appointmentData = $request->validated(['car_id', 'service_id']);
        $carData = $request->validated(['make', 'model', 'vin']);

        $hasActiveAppointment = false;
        if ($appointmentData['car_id']) {
            $car = Car::find($appointmentData['car_id']);

            $hasActiveAppointment = $car->appointments()->whereNull('completed_at')->exists();
        }

        if ($hasActiveAppointment) {
            return response()->json([
                'message' => 'Car already has an active appointment',
            ], Response::HTTP_CONFLICT);
        }


        $appointment = $this->handle($appointmentData['car_id'], $appointmentData['service_id'], $carData);

        return response()->json($appointment, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN);
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'car_id' => 'nullable|exists:cars,id',
            'vin' => 'required|string',
            'make' => 'required_without:car_id|string',
            'model' => 'required_without:car_id|string',
        ];
    }
}
