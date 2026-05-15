<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            $table->decimal('temp_warning', 5, 2)->default(35.00);
            $table->decimal('temp_critical', 5, 2)->default(45.00);
            $table->decimal('smoke_warning', 5, 2)->default(10.00);
            $table->decimal('smoke_critical', 5, 2)->default(15.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thresholds');
    }
};