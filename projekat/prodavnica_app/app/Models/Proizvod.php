<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proizvod extends Model
{
    protected $fillable = [
        'naziv',
        'kategorija',
        'cena',
        'dostupna_kolicina',
        'tip',
    ];
    
}
