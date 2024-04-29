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
        Schema::create('diets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->unsignedTinyInteger('type')->default(1);
            $table->unsignedInteger('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diets');
    }
};
