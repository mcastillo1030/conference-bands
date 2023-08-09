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
        Schema::create('bracelets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('name')->nullable();
            $table->string('group')->nullable();
            $table->enum('status', ['system', 'reserved', 'registered'])->default('system');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bracelets');
    }
};
