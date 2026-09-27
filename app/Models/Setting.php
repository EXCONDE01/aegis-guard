<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_name', 
        'admin_email', 
        'pushover_app_token', 
        'pushover_user_key', 
        'alerts_muted',
        'log_retention_days',       // New field
        'sensor_polling_interval'   // New field
    ];
}