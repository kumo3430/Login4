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
        Schema::create('recurring_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instance_id')->references('id')->on('recurring_instances')->onDelete('cascade');
            $table->timestamp('check_datetime');
            $table->unsignedInteger('current_value')->nullable();
            $table->time('sleep_time')->nullable();
            $table->time('wake_up_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_checks');
    }
};
