<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'stop_id',
        'name',
        'email',
        'password',
        'scheduled_time',
        'scheduled_time_start',
        'scheduled_time_end',
        'address',
        'latitude',
        'longitude',
        'receipt_path',
        'payment_method',
        'boarded',
        'boarded_at',
        'boarded_latitude',
        'boarded_longitude',
        'first_access',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'boarded_at' => 'datetime',
        'boarded' => 'boolean',
        'first_access' => 'boolean',
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
