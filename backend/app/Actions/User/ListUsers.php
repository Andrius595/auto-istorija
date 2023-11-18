<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListUsers
{
    use AsAction;

    public function handle(int $perPage, array $searchParams = [], array $relations = []): LengthAwarePaginator
    {
        $query = User::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($searchParams as $searchParam) {
            match ($searchParam) {
                'service_title' => $query->whereHas('service', function ($query) use ($searchParam) {
                    $query->where('title', 'like', "%$searchParam%");
                }),
                'role_name' => $query->whereHas('roles', function ($query) use ($searchParam) {
                    $query->where('name', $searchParam);
                }),
                default => $query->where($searchParam, 'like', "%$searchParam%"),
            };
        }

        return $query->paginate($perPage);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $searchParams = $request->only(['first_name', 'last_name', 'email', 'service_title', 'role_name']);

        return response()->json($this->handle($request->get('perPage', 10), $searchParams));
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
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|string',
            'service_title' => 'nullable|string',
            'role_name' => 'nullable|string',
        ];
    }
}
