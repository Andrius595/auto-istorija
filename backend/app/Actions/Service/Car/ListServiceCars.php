<?php

namespace App\Actions\Service\Car;

use App\Actions\Car\ListCars;
use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListServiceCars
{
    use AsAction;

    public function handle(int $perPage, Service $service, array $searchParams = []): LengthAwarePaginator
    {
        $searchParams['service_id'] = $service->id;

        return ListCars::run($perPage, $searchParams, ['activeAppointment']);
    }

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        $perPage = $request->get('perPage');
        $searchParams = $request->only([
            'make',
            'model',
        ]);

        return response()->json(
            $this->handle(
                $perPage, $service, $searchParams
            )
        );
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_LIST_CARS);
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required|int|min:1',
            'page' => 'required|int|min:1',
            'make' => 'nullable|string',
            'model' => 'nullable|string',
            'vin' => 'nullable|string',
            'owner' => 'nullable|string',
        ];
    }
}
