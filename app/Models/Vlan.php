<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vlan extends Model
{
    protected $fillable = ['vlan_id', 'name', 'subnet', 'status'];
}