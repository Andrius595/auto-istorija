<?php

namespace App\Actions\Service;

use App\Config\PermissionsConfig;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListServices
{
    use AsAction;

    public function handle(int $perPage, string $title = null): LengthAwarePaginator
    {
        $query = Service::query();

        if ($title) {
            $query->where('title', 'like', "%$title%");
        }

        return $query->paginate($perPage);
    }

    public function asController(ActionRequest $request, Service $service): JsonResponse
    {
        return response()->json($this->handle(10));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_LIST_SERVICES);
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required|int|min:1',
            'page' => 'required|int|min:1',
            'title' => 'nullable|string',
        ];
    }
}
