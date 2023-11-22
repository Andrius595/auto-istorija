<?php

namespace App\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RefreshToken
{
    use AsAction;

    public function handle(): mixed
    {
        return auth()->refresh();
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $token = $this->handle();

        if (!$token) {
            return response()->json(['error' => 'Refresh token already expired'], 401);
        }

        return response()->json([
            'token' => $token,
        ]);
    }
}
