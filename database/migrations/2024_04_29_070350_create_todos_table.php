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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedTinyInteger('category_id');
            $table->string('label')->nullable($value = true);
            $table->string('title');
            $table->string('introduction');
            $table->unsignedTinyInteger('frequency')->default(0);
            $table->string('note')->nullable($value = true);
            $table->date('start_at');
            $table->date('due_at');
            $table->time('reminder_time');
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
