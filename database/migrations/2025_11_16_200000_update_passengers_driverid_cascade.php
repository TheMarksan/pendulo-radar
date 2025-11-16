<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')
                ->references('id')->on('drivers')
                ->onDelete('cascade')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->foreign('driver_id')
                ->references('id')->on('drivers')
                ->onDelete('set null')
                ->change();
        });
    }
};
