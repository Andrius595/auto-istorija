<?php

namespace App\Actions\Auth;

use App\Actions\User\ShowUser;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserSession
{
    use AsAction;

    public function asController(ActionRequest $request): JsonResponse
    {
        $user = ShowUser::run(auth()->user());
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()->pluck('name');

        return response()->json([
            ...$user->toArray(),
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}
