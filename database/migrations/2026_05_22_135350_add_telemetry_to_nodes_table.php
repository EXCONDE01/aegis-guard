<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('specific_area');
            $table->integer('latency')->nullable()->after('ip_address'); // Measured in ms
            $table->string('uptime')->nullable()->after('latency');
        });
    }

    public function down()
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'latency', 'uptime']);
        });
    }
};