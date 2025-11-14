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
        Schema::table('passengers', function (Blueprint $table) {
            // Change scheduled_time to time range
            $table->time('scheduled_time_start')->nullable()->after('scheduled_time');
            $table->time('scheduled_time_end')->nullable()->after('scheduled_time_start');
            
            // Boarding confirmation
            $table->boolean('boarded')->default(false)->after('receipt_path');
            $table->dateTime('boarded_at')->nullable()->after('boarded');
            $table->decimal('boarded_latitude', 10, 8)->nullable()->after('boarded_at');
            $table->decimal('boarded_longitude', 11, 8)->nullable()->after('boarded_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn([
                'scheduled_time_start',
                'scheduled_time_end',
                'boarded',
                'boarded_at',
                'boarded_latitude',
                'boarded_longitude'
            ]);
        });
    }
};
