<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'pix_key',
        'access_key',
        'route_id',
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
