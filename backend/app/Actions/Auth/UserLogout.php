<?php

namespace App\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UserLogout
{
    use AsAction;

    public function handle(): void
    {
        auth()->logout();
    }

    public function asController(ActionRequest $request): JsonResponse
    {
       $this->handle();

       return response()->json();
    }
}
