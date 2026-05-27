<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirewallRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'interface', 
        'protocol',
        'source',
        'destination',
        'port',
        'policy',
        'is_synced'
    ];
}