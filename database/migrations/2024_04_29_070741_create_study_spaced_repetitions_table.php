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
        Schema::create('study_spaced_repetitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->date('day1_date');
            $table->unsignedTinyInteger('day1_status')->default(0);
            $table->date('day3_date');
            $table->unsignedTinyInteger('day3_status')->default(0);
            $table->date('day7_date');
            $table->unsignedTinyInteger('day7_status')->default(0);
            $table->date('day14_date');
            $table->unsignedTinyInteger('day14_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_spaced_repetitions');
    }
};
