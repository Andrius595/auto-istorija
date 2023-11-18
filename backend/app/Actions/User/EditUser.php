<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class EditUser
{
    use AsAction;

    public function handle(User|int $user, array $data, string $role): bool
    {
        if (is_int($user)) {
            $user = User::findOrFail($user);
        }

        $user->syncRoles($role);

        return $user->update($data);
    }

    public function asController(ActionRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        $role = PermissionsConfig::CLIENT_ROLE;

        if ($request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN) && isset($data['role']))  {
            $role = $data['role'];
        }

        $this->handle($user, $data, $role);

        return response()->json($user);
    }

    public function rules(ActionRequest $request): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->route('user')->id)],
            'service_id' => 'nullable|exists:services,id',
            'role' => ['nullable', Rule::in(PermissionsConfig::ROLES)],
        ];
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_EDIT_USER);
    }
}
