<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    protected $fillable = [
        'hardware_id', 
        'location_name', 
        'specific_area', 
        'status',
        'ip_address',
        'latency',
        'uptime',
        // --- NEW ZONAL OVERRIDE COLUMNS ---
        'has_custom_thresholds',
        'custom_temp_warning',
        'custom_temp_critical',
        'custom_smoke_warning',
        'custom_smoke_critical'
    ];

    public function logs()
    {
        return $this->hasMany(NodeLog::class);
    }
}