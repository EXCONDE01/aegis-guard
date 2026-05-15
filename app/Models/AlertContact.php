<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertContact extends Model {
    protected $fillable = ['name', 'role', 'phone', 'is_active'];
}