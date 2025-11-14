<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_access',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'first_access' => 'boolean',
    ];
}
