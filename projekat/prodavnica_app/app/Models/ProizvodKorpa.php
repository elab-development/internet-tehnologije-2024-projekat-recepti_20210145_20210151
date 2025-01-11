<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProizvodKorpa extends Model
{
    protected $table = 'proizvod_korpas'; // Obezbeđujemo da je povezano sa ispravnom pivot tabelom

    protected $fillable = ['korpa_id', 'proizvod_id', 'kolicina_proizvoda'];
}
