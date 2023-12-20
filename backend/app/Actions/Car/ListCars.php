<?php

namespace App\Actions\Car;

use App\Config\PermissionsConfig;
use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListCars
{
    use AsAction;

    public function handle(int $perPage, array $searchParams = [], $relations = [], $sortParams = []): LengthAwarePaginator
    {
        $query = Car::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($searchParams as $key => $searchParam) {
            match ($key) {
                'owner_id' => $query->where('owner_id', $searchParam),
                'owner_first_name' => $query->whereHas('owner', function ($q) use ($searchParam) {
                    $q->where('first_name', 'like', "%$searchParam%");
                }),
                'service_id' => $query->whereHas('appointments', function ($q) use ($searchParam) {
                    $q->where('completed_at', null)
                        ->where('service_id', $searchParam);
                }),
                default => $query->where($key, 'like', "%$searchParam%"),
            };
        }

        if (!empty($sortParams)) {
            match ($sortParams['sortBy']) {
                'owner' => $query->join('users', 'cars.owner_id', '=', 'users.id')
                    ->orderBy('users.first_name', $sortParams['sortDirection']),
                default => $query->orderBy($sortParams['sortBy'], $sortParams['sortDirection']),
            };
        }

        return $query->paginate($perPage);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $searchParams = $request->only([
            'owner_id',
            'owner_first_name',
            'service_id',
            'make',
            'model',
        ]);

        $sortParams = $request->only([
            'sortBy',
            'sortDirection',
        ]);

        return response()->json($this->handle($request->get('perPage', 10), $searchParams, ['owner'], $sortParams));
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
            'owner_id' => 'nullable|string',
            'service_id' => 'nullable|string',
            'owner_first_name' => 'nullable|string',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc',
        ];
    }
}
