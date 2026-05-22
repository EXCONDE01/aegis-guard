<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use HasFactory;

    // ADDED: ip_address, latency, uptime
    protected $fillable = [
        'hardware_id', 
        'location_name', 
        'specific_area', 
        'status',
        'ip_address',
        'latency',
        'uptime'
    ];

    public function logs()
    {
        return $this->hasMany(NodeLog::class);
    }
}