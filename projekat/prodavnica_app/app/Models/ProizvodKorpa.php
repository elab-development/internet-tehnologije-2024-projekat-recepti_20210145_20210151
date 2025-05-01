<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProizvodKorpa extends Model
{
    use HasFactory;
    
    protected $table = 'proizvod_korpas'; // Obezbeđujemo da je povezano sa ispravnom pivot tabelom

    protected $fillable = ['korpa_id', 'proizvod_id', 'kolicina_proizvoda'];

    public function korpa()
    {
        return $this->belongsTo(Korpa::class, 'korpa_id');
    }
    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id');
    }
}
