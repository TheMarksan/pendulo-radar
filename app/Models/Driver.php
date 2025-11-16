<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    public function cars()
    {
        return $this->hasMany(\App\Models\Car::class);
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'pix_key',
        'access_key',
        'route_id',
        'first_access',
    ];
    protected $casts = [
        'first_access' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];


    public function schedules()
    {
        return $this->hasMany(DriverSchedule::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
