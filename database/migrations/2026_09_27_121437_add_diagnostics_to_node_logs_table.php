<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('node_logs', function (Blueprint $table) {
        $table->integer('wifi_rssi')->nullable()->after('smoke_level');
        $table->unsignedBigInteger('uptime_seconds')->nullable()->after('wifi_rssi');
        $table->boolean('is_calibrating')->default(false)->after('uptime_seconds');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('node_logs', function (Blueprint $table) {
            //
        });
    }
};
