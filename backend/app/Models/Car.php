<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'make',
        'model',
        'vin',
        'year_of_manufacture',
    ];

    public const MILEAGE_TYPE_KILOMETERS = 1;
    public const MILEAGE_TYPE_MILES = 2;


    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'car_id', 'id');
    }

    public function activeAppointment(): HasOne
    {
        return $this->hasOne(Appointment::class)->where('completed_at', null);
    }
}
