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
        Schema::create('kupovinas', function (Blueprint $table) {
            $table->id('id_kupovine'); // Primarni ključ

            $table->foreignId('id')->constrained('users')->onDelete('cascade'); // Veza sa postojećim User modelom
            $table->decimal('ukupna_cena', 10, 2); // Ukupna cena kupovine
            $table->string('nacin_placanja'); // Način plaćanja (kartica, keš, itd.)
            $table->string('adresa_dostave'); // Adresa dostave
            $table->timestamps(); // Timestamps (created_at i updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kupovinas');
    }
};
