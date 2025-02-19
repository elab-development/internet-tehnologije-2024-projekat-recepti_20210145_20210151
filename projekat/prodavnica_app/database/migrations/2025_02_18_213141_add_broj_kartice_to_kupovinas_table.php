<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrojKarticeToKupovinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kupovinas', function (Blueprint $table) {
            // Dodajemo novu kolonu 'broj_kartice'
            $table->string('broj_kartice')->nullable(); // Koristite nullable() ako je broj kartice opcionalan
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kupovinas', function (Blueprint $table) {
            // Uklanjamo kolonu 'broj_kartice' ako migraciju vraćamo
            $table->dropColumn('broj_kartice');
        });
    }
}
