<?php

namespace App\Actions\Record;

use App\Models\Appointment;
use App\Models\Car;
use App\Models\Record;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use PermissionsConfig;

class ShowRecord
{
    use AsAction;

    public function handle(Record|int $record): Record
    {
        if (is_int($record)) {
            return Record::findOrFail($record);
        }

        return $record;
    }

    public function asController(ActionRequest $request, Record $record): JsonResponse
    {
        return response()->json($this->handle($record));
    }

    public function authorize(ActionRequest $request): bool
    {
        $record = $request->route('record');
        $user = auth()->user();


        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_VIEW_RECORD) &&
            ($user->id == $record->appointment->car->user_id || $user->hasRole(PermissionsConfig::SYSTEM_ADMIN));
}
}
