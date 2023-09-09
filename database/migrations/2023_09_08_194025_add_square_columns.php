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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('id_key')->nullable();
            $table->string('payment_link')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('square_order_id')->nullable();
            $table->string('order_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'id_key',
                'payment_link',
                'payment_status',
                'square_order_id',
                'order_notes',
            ]);
        });
    }
};
