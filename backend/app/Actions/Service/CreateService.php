<?php

namespace App\Actions\Service;

use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateService
{
    use AsAction;

    public function handle(array $data): Service
    {
        return Service::create($data);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $service = $this->handle($data);

        return response()->json($service, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_SERVICE);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
