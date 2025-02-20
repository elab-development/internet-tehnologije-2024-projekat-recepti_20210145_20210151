<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proizvod extends Model
{
    use HasFactory;

    protected $table = 'proizvods'; // Laravel očekuje 'proizvods' umesto 'proizvodi'
    protected $primaryKey = 'id';
    public $timestamps = true;

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
                ->withPivot('kolicina','merna_jedinica') // Da bismo dohvatili količinu iz pivot tabele
                ->withTimestamps();      // Ako su potrebni timestampi
    }

    public function korpe()
    {
        return $this->belongsToMany(Korpa::class, 'proizvod_korpas','proizvod_id', 'korpa_id')
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
