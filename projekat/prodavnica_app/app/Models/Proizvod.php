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
        return $this->belongsToMany(Recept::class, 'proizvod_recept','proizvod_id', 'recept_id')
                ->withPivot('kolicina')  // Da bismo dohvatili količinu iz pivot tabele
                ->withTimestamps();      // Ako su potrebni timestampi
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
