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
        Schema::create('recurring_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('todo_id')->references('id')->on('todos')->onDelete(('cascade'));
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('total_value')->nullable();
            $table->unsignedTinyInteger('occurrence_status')->default(0);
            $table->unsignedTinyInteger('is_added')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_instances');
    }
};
