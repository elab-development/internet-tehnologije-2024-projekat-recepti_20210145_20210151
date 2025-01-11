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
        Schema::create('proizvod_korpas', function (Blueprint $table) {
            $table->unsignedBigInteger('korpa_id');
            $table->unsignedBigInteger('proizvod_id');
            $table->integer('kolicina_proizvoda'); // Količina proizvoda u korpi
            $table->timestamps();

            $table->foreign('korpa_id')->references('id')->on('korpas')->onDelete('cascade');
            $table->foreign('proizvod_id')->references('id')->on('proizvods')->onDelete('cascade');

            $table->primary(['korpa_id', 'proizvod_id']); // Kombinovani primarni ključ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proizvod_korpas');
    }
};
