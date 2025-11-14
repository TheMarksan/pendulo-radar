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
        Schema::create('trip_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('stop_id')->constrained()->onDelete('cascade');
            $table->date('trip_date');
            $table->time('time_start');
            $table->enum('direction', ['outbound', 'return'])->default('outbound');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            // Índice composto para evitar duplicatas
            $table->unique(['driver_id', 'stop_id', 'trip_date', 'time_start', 'direction'], 'trip_progress_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_progress');
    }
};
