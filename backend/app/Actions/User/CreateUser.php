<?php

namespace App\Actions\User;

use App\Config\PermissionsConfig;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response;

class CreateUser
{
    use AsAction;

    public function handle(array $data, string $role): User
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::query()->create($data);

        $user->assignRole($role);

        return $user;
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $role = PermissionsConfig::CLIENT_ROLE;

        if ($request->user()->hasRole(PermissionsConfig::SYSTEM_ADMIN) && isset($data['role'])) {
            $role = $data['role'];
        }

        $user = $this->handle($data, $role);

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function authorize(ActionRequest $request)
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_CREATE_USER);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'service_id' => 'nullable|exists:services,id',
            'password' => 'required|string|min:8',
            'role' => ['nullable', Rule::in(PermissionsConfig::ROLES)]
        ];
    }
}
