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
        Schema::table('proizvod_recept', function (Blueprint $table) {
            $table->decimal('kolicina', 8, 2); // ili neki drugi tip koji odgovara (npr. integer)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proizvod_recept', function (Blueprint $table) {
            $table->dropColumn('kolicina');
        });
    
    }
};
