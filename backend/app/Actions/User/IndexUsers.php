<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class IndexUsers
{
    use AsAction;

    public function handle(): Collection
    {
        return User::all();
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        return response()->json($this->handle());
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_LIST_USERS);
    }
}
