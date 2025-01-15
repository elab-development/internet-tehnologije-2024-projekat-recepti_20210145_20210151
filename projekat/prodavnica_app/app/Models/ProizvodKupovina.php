<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProizvodKupovina extends Model
{
    use HasFactory;
    protected $table = 'proizvod_kupovinas'; // Naziv pivot tabele

    protected $fillable = [
        'proizvod_id', 'id_kupovine', 'kolicina'
    ];
}
