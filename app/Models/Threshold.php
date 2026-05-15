<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $fillable = [
        'temp_warning',
        'temp_critical',
        'smoke_warning',
        'smoke_critical'
    ];
}