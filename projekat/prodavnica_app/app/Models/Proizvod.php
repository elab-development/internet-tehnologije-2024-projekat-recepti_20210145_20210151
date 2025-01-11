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
    public function recepti()
    {
        //return $this->belongsToMany(Recept::class, 'recept_proizvod');
        return $this->belongsToMany(Recept::class)
                ->as('proizvod_recept')
                ->withTimestamps();
    }
    public function korpe()
    {
        return $this->belongsToMany(Korpa::class, 'proizvod_korpas')
                    ->withPivot('kolicina_proizvoda')
                    ->withTimestamps();
    }
    public function kupovine()
    {
        return $this->belongsToMany(Kupovina::class, 'proizvod_kupovinas', 'proizvod_id', 'id_kupovine')
                    ->withPivot('kolicina') // Pivot polje za količinu
                    ->withTimestamps(); // Timestamps za pivot tabelu
    }

}
