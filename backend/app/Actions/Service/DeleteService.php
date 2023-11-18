<?php

namespace App\Actions\Service;

use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteService
{
    use AsAction;

    public function handle(Service|int $service): bool
    {
        if (is_int($service)) {
            $service = Service::findOrFail($service);
        }

        return (bool)$service->delete();
    }

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        $this->handle($service);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_DELETE_SERVICE);
    }
}
