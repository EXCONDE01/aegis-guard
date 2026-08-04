<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            
            // Keep your temperature as a decimal (since DHT22 outputs like 31.5)
            $table->decimal('temp_warning', 4, 1)->nullable(); 
            $table->decimal('temp_critical', 4, 1)->nullable();

            // Change the smoke columns to integers
            $table->integer('smoke_warning')->nullable();
            $table->integer('smoke_critical')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thresholds');
    }
};