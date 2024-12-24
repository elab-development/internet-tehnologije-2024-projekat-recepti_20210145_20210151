<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proizvod extends Model
{
    use HasFactory;

    protected $table = 'proizvodi';

    protected $fillable = [
        'naziv',
        'kategorija',
        'cena',
        'kolicina',
        'tip',
    ];
}
