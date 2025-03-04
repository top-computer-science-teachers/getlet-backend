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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('order_type', ['send', 'take'])->default('send');
            $table->enum('order_status', [
                'pending',
                'in_way',
                'completed',
                'failed',
            ])->default('pending');

            $table->string('object');

            $table->string('date');

            $table->enum('price_type', ['fix', 'contract'])->default('fix');
            $table->bigInteger('price')->nullable();

            $table->string('sender_contact')->nullable();
            $table->string('receiver_contact')->nullable();

            $table->foreignUuid('from_city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignUuid('to_city_id')->constrained('cities')->cascadeOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
