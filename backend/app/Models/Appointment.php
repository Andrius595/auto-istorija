<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    protected $fillable = [
        'service_id',
        'car_id',
        'description',
        'confirmed_at',
        'completed_at',
    ];

    public function records(): HasMany
    {
        return $this->hasMany(Record::class);
    }
}
