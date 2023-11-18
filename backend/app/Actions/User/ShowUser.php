<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class ShowUser
{
    use AsAction;

    public function handle(User|int $user): User
    {
        if (is_int($user)) {
            return User::findOrFail($user);
        }

        return $user;
    }

    public function asController(ActionRequest $request, User $user): JsonResponse
    {
        return response()->json($this->handle($user));
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_VIEW_USER);
    }
}
