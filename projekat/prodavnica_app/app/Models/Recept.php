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
    protected $table = 'recepts'; // Naziv tabele u bazi
    protected $primaryKey = 'id'; // Laravel podrazumevano koristi 'id', tako da je ovo ispravno
    public $timestamps = true;
    
    public function proizvodi()
    {
                return $this->belongsToMany(Proizvod::class, 'proizvod_recept', 'recept_id', 'proizvod_id')
                ->withPivot('kolicina', 'merna_jedinica')// Povlačenje količine iz pivot tabele
                ->withTimestamps();  
    }
}
