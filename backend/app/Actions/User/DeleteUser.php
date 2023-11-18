<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class DeleteUser
{
    use AsAction;

    public function handle(User|int $user): bool
    {
        if (is_int($user)) {
            $user = User::findOrFail($user);
        }

        return (bool)$user->delete();
    }

    public function asController(ActionRequest $request, User $user): JsonResponse
    {
        $this->handle($user);

        return response()->json(null,Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_DELETE_USER);
    }
}
