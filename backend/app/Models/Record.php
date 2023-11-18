<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Record extends Model
{
    protected $fillable = [
        'current_mileage',
        'mileage_type',
        'description',
    ];
}
