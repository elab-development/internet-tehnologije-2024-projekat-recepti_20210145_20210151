<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProizvodRecept extends Model
{
    protected $fillable = ['proizvod_id', 'recept_id', 'kolicina'];
    
    protected $table = 'proizvod_recept'; // Ime tabele koju koristiš u bazi

    public $timestamps = true;

    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id');
    }

    public function recept()
    {
        return $this->belongsTo(Recept::class, 'recept_id');
    }
}
