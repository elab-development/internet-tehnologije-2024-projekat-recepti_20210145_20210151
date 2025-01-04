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
        Schema::create('proizvods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('naziv'); // Naziv proizvoda
            $table->string('kategorija'); // Kategorija proizvoda
            $table->decimal('cena', 8, 2); // Cena proizvoda
            $table->integer('dostupna_kolicina'); // Dostupna količina proizvoda
            $table->enum('tip', ['organski', 'neorganski']); // Tip proizvoda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proizvods');
    }
};
