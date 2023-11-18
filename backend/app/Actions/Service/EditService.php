<?php

namespace App\Actions\Service;

use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditService
{
    use AsAction;

    public function handle(Service|int $service, array $data): bool
    {
        if (is_int($service)) {
            $service = Service::firstOrFail($service);
        }

        return $service->update($data);
    }

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        $data = $request->validated();

        $this->handle($service, $data);

        return response()->json($service);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'nullable|string', // TODO handle nullable field
        ];
    }

    public function authorize(ActionRequest $request): bool
    {
        // TODO
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_EDIT_SERVICE);
    }
}
