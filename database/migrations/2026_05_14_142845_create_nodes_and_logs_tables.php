<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Module 2: Sensor Management - Tracks hardware connectivity 
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->string('hardware_id')->unique(); // e.g., ESP32-LAB-01 [cite: 183]
            $table->string('location_name'); // e.g., CCS Comp Lab 1 [cite: 440]
            $table->string('specific_area')->nullable(); 
            $table->string('status')->default('SAFE'); // SAFE, WARNING, CRITICAL [cite: 182, 369]
            $table->timestamps();
        });

        // Module 5: History Log - Archives all past readings [cite: 68, 179]
        Schema::create('node_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained()->onDelete('cascade');
            $table->decimal('temperature', 5, 2); // DHT sensor readings [cite: 183, 567]
            $table->integer('smoke_level'); // MQ-Series sensor readings [cite: 183, 567]
            $table->integer('water_level')->default(0); // Ultrasonic sensor readings [cite: 183, 567]
            $table->string('status'); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('node_logs');
        Schema::dropIfExists('nodes');
    }
};