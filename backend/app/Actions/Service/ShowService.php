<?php

namespace App\Actions\Service;

use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowService
{
    use AsAction;

    public function handle(Service|int $service): Service
    {
        if (is_int($service)) {
            return Service::findOrFail($service);
        }

        return $service;
    }

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        return response()->json($this->handle($service));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_VIEW_SERVICE);
}
}
