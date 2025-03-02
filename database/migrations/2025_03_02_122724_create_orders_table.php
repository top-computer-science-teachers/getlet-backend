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

            $table->enum('type', ['send', 'take'])->default('send');
            $table->string('object');
            $table->string('date');
            $table->string('price');
            $table->string('sender_contact');
            $table->string('receiver_contact');

            $table->foreignUuid('from_country_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignUuid('from_city_id')->constrained('cities')->cascadeOnDelete();

            $table->foreignUuid('to_country_id')->constrained('cities')->cascadeOnDelete();
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
