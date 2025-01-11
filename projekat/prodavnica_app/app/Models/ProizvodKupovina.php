<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProizvodKupovina extends Model
{
    protected $table = 'proizvod_kupovinas'; // Naziv pivot tabele

    protected $fillable = [
        'proizvod_id', 'id_kupovine', 'kolicina'
    ];
}
