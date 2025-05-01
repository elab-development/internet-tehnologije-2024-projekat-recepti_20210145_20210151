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

        // Dodavanje CHECK ograničenja za količinu proizvoda u korpi
        Schema::table('proizvod_korpas', function (Blueprint $table) {
            DB::statement('ALTER TABLE proizvod_korpas ADD CONSTRAINT check_kolicina_proizvoda CHECK (kolicina_proizvoda > 0)');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Brisanje CHECK ograničenja
        Schema::table('proizvod_korpas', function (Blueprint $table) {
            DB::statement('ALTER TABLE proizvod_korpas DROP CONSTRAINT check_kolicina_proizvoda');
        });
    }
};
