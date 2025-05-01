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
        Schema::table('recepts', function (Blueprint $table) {
            $table->string('slika')->nullable()->after('opis_pripreme'); // Dodavanje kolone slika
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepts', function (Blueprint $table) {
            $table->dropColumn('slika'); // Brisanje kolone ako se migracija rollback-uje
        });
    }
};
