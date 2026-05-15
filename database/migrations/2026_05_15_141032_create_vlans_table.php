<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vlans', function (Blueprint $table) {
            $table->id();
            $table->integer('vlan_id')->unique();
            $table->string('name');
            $table->string('subnet');
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vlans');
    }
};