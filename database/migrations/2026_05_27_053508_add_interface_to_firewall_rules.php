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
        Schema::table('firewall_rules', function (Blueprint $table) {
            // Adds the missing column right after the 'id' column
            $table->string('interface')->default('lan')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('firewall_rules', function (Blueprint $table) {
            $table->dropColumn('interface');
        });
    }
};
