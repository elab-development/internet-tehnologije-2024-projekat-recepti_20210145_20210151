<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recept extends Model
{

    use HasFactory;

    protected $fillable = [
        'naziv',
        'tip_jela',
        'vreme_pripreme',
        'opis_pripreme'
    ];
    public function proizvodi()
    {
        //return $this->belongsToMany(Proizvod::class, 'recept_proizvod');
        /*return $this->belongsToMany(Proizvod::class)
                ->as('proizvod_recept')
                ->withTimestamps();*/
                return $this->belongsToMany(Proizvod::class, 'proizvod_recept', 'recept_id', 'proizvod_id')
                ->withPivot('kolicina');  // Povlačenje količine iz pivot tabele
    }
}
