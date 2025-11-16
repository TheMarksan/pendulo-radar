<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverSchedule extends Model
{
    use HasFactory;


    protected $fillable = [
        'driver_id',
        'route_id',
        'date',
        'departure_time',
        'return_time',
        'is_active',
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
