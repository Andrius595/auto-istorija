<?php

namespace App\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserLogin
{
    use AsAction;

    public function handle($email, $password): string|null
    {
        return auth()->attempt(['email' => $email, 'password' => $password]);
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $token = $this->handle(
           $request->get('email'),
           $request->get('password')
        );

        if (!$token) {
           return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }


    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
