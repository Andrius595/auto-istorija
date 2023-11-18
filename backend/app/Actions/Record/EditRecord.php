<?php

namespace App\Actions\Record;

use App\Models\Appointment;
use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use PermissionsConfig;

class EditRecord
{
    use AsAction;

    public function handle(Record|int $record, array $data): bool
    {
        if (is_int($record)) {
            $record = Record::findOrFail($record);
        }

        return $record->update($data);
    }

    public function asController(ActionRequest $request, Record $record): JsonResponse
    {
        $data = $request->validated();

        $this->handle($record, $data);

        return response()->json($record);
    }

    public function rules(): array
    {
        return [
            'description' => 'nullable|string', // TODO handle nullable field
        ];
    }

    public function authorize(ActionRequest $request): bool
    {
        // TODO what about service admins?
        return $request->user()->hasPermissionTo(PermissionsConfig::CAN_EDIT_SERVICE);
    }
}
