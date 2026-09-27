<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodeLog extends Model {
    protected $fillable = ['node_id', 'temperature', 'smoke_level', 'water_level', 'status','wifi_rssi', 'uptime_seconds', 'is_calibrating'];

    public function node() {
        return $this->belongsTo(Node::class);
    }
}