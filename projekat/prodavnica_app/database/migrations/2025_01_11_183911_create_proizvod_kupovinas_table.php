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
        Schema::create('proizvod_kupovinas', function (Blueprint $table) {
            $table->foreignId('proizvod_id')->constrained('proizvods')->onDelete('cascade'); // Veza sa tabelom Proizvod
            $table->foreignId('id_kupovine')->constrained('kupovinas', 'id_kupovine')->onDelete('cascade'); // Veza sa tabelom Kupovina
            $table->integer('kolicina'); // Količina proizvoda u kupovini
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proizvod_kupovinas');
    }
};
