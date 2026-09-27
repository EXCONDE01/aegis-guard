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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('campus_name')->default('Laguna State Polytechnic University');
        $table->string('admin_email')->default('admin@lspu.edu.ph');
        $table->string('pushover_app_token')->nullable();
        $table->string('pushover_user_key')->nullable();
        $table->boolean('alerts_muted')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
