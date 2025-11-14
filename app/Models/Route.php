<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'has_return',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_return' => 'boolean',
    ];

    public function stops()
    {
        return $this->hasMany(Stop::class)->orderBy('order');
    }

    public function outboundStops()
    {
        return $this->hasMany(Stop::class)->where('type', 'outbound')->orderBy('order');
    }

    public function returnStops()
    {
        return $this->hasMany(Stop::class)->where('type', 'return')->orderBy('order');
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
