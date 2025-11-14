<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripProgress extends Model
{
    protected $table = 'trip_progress';

    protected $fillable = [
    'driver_id',
        'stop_id',
        'trip_date',
        'time_start',
        'direction',
        'confirmed_at',
    ];

    protected $casts = [
        'trip_date' => 'date',
        'confirmed_at' => 'datetime',
    ];


    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }
}
