<?php

namespace App\Actions\Record;

use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use PermissionsConfig;
use Symfony\Component\HttpFoundation\Response;

class DeleteRecord
{
    use AsAction;

    public function handle(Record|int $record): bool
    {
        if (is_int($record)) {
            $record = Record::find($record);
        }

        return (bool)$record->delete();
    }

    public function asController(ActionRequest $request, Record $record): JsonResponse
    {
        $this->handle($record);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function authorize(ActionRequest $request): bool
    {
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_DELETE_SERVICE);
    }
}
