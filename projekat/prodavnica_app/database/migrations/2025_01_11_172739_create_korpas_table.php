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
        Schema::create('korpas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Povezivanje sa korisnikom
            $table->decimal('ukupna_cena', 8, 2)->default(0); // Ukupna cena
            $table->enum('status', ['aktivan', 'kompletan', 'otkazan'])->default('aktivan');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('korpas');
    }
};
