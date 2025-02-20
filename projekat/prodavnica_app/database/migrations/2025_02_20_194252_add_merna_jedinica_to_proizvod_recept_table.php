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
            Schema::table('proizvod_recept', function (Blueprint $table) {
                $table->string('merna_jedinica', 10)->after('kolicina')->nullable(); // Dodajemo kolonu nakon 'kolicina'
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proizvod_recept', function (Blueprint $table) {
            $table->dropColumn('merna_jedinica'); // Brišemo kolonu ako treba rollback
        });
    }
};
