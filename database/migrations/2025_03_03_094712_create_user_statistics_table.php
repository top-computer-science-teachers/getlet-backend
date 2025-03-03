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
        Schema::create('user_statistics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->bigInteger('order_take_created_count')->default(0);
            $table->bigInteger('order_take_count')->default(0);
            $table->bigInteger('order_take_completed_count')->default(0);
            $table->bigInteger('order_take_failed_count')->default(0);

            $table->bigInteger('order_send_created_count')->default(0);
            $table->bigInteger('order_send_count')->default(0);
            $table->bigInteger('order_send_completed_count')->default(0);
            $table->bigInteger('order_send_failed_count')->default(0);

            //todo: need to add other stats

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_statistics');
    }
};
