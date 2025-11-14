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
        // Obsoleto: cpf e password já tratados na criação da tabela
        // Schema::table('passengers', function (Blueprint $table) {
        //     $table->dropColumn('cpf');
        //     $table->string('email')->after('name');
        //     $table->string('password', 8)->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('passengers', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->string('cpf', 14)->after('name');
            $table->string('password', 4)->change();
        });
    }
};
