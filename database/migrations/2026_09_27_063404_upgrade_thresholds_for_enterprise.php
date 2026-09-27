<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add Calibration & Audit to Thresholds
        Schema::table('thresholds', function (Blueprint $table) {
            $table->float('temp_offset')->default(0.0)->after('temp_critical');
            $table->integer('smoke_offset')->default(0)->after('smoke_critical');
            $table->string('updated_by_name')->nullable()->after('smoke_offset');
        });

        // 2. Add Zonal Override Foundation to Nodes
        Schema::table('nodes', function (Blueprint $table) {
            $table->boolean('has_custom_thresholds')->default(false)->after('status');
            $table->float('custom_temp_warning')->nullable()->after('has_custom_thresholds');
            $table->float('custom_temp_critical')->nullable()->after('custom_temp_warning');
            $table->integer('custom_smoke_warning')->nullable()->after('custom_temp_critical');
            $table->integer('custom_smoke_critical')->nullable()->after('custom_smoke_warning');
        });
    }

    public function down(): void
    {
        Schema::table('thresholds', function (Blueprint $table) {
            $table->dropColumn(['temp_offset', 'smoke_offset', 'updated_by_name']);
        });
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn(['has_custom_thresholds', 'custom_temp_warning', 'custom_temp_critical', 'custom_smoke_warning', 'custom_smoke_critical']);
        });
    }
};