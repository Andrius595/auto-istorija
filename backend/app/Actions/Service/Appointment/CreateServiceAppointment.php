<?php

namespace App\Actions\Service\Appointment;

use App\Actions\Appointment\CreateAppointment;
use App\Config\PermissionsConfig;
use App\Models\Appointment;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateServiceAppointment
{
    use AsAction;

    public function asController(ActionRequest $request, Car $car): JsonResponse
    {
        $data = $request->validated();

        if ($data['car_id']) {
            $car = Car::findOrFail($data['car_id']);
        } else {
            $car = Car::create([
                'make' => $data['make'],
                'model' => $data['model'],
                'vin' => $data['vin'],
            ]);
        }

        // TODO check if car does not have an active appointment

        $appointment = CreateAppointment::run($car->id, $request->user()->service_id);

        return response()->json($appointment, Response::HTTP_CREATED);
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
