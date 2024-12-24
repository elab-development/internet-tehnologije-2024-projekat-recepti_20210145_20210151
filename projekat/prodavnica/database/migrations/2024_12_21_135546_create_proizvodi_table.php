<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proizvodi', function (Blueprint $table) {
            $table->id();
            $table->string('naziv');
            $table->string('kategorija');
            $table->decimal('cena', 8, 2);
            $table->integer('kolicina');
            $table->enum('tip', ['organski', 'neorganski']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proizvodi');
    }
};
