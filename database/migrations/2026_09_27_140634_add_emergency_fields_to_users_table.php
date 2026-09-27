<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('mobile_number')->nullable()->after('email');
        $table->boolean('alert_warnings')->default(true)->after('mobile_number');
        $table->boolean('alert_critical')->default(true)->after('alert_warnings');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['mobile_number', 'alert_warnings', 'alert_critical']);
    });
}
};
