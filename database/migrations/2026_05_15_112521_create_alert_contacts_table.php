<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alert_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role'); // e.g., Lab Custodian, Safety Officer
            $table->string('phone'); // e.g., +639123456789
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alert_contacts');
    }
};