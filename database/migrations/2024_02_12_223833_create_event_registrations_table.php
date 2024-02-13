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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('registration_id')->unique();
            $table->string('name');
            $table->integer('guests');
            $table->timestamp('event_date')->nullable();
            $table->string('event_location')->default('The Venue');
            $table->timestamp('checkedin_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
