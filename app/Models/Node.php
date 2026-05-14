<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {
    protected $fillable = ['hardware_id', 'location_name', 'specific_area', 'status'];

    public function logs() {
        return $this->hasMany(NodeLog::class)->latest(); // Latest history first [cite: 179]
    }
}