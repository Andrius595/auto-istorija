<?php

namespace App\Actions\User\Car;

use App\Actions\Car\ListCars;
use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ListUserCars
{
    use AsAction;

    public function handle(int $perPage, User $user, array $searchParams = []): LengthAwarePaginator
    {
        $searchParams['owner_id'] = $user->id;

        return ListCars::run($perPage, $searchParams);
    }

    public function asController(ActionRequest $request, User $user): JsonResponse
    {
        $perPage = $request->get('perPage');
        $searchParams = $request->only([
            'make',
            'model',
            'vin'
        ]);

        return response()->json(
            $this->handle(
                $perPage, $user, $searchParams
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
        ];
    }
}
