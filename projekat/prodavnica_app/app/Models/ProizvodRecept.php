<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProizvodRecept extends Model
{
    use HasFactory;
    protected $fillable = ['proizvod_id', 'recept_id', 'kolicina', 'merna_jedinica'];
    
    protected $table = 'proizvod_recept'; // Ime tabele koju koristiš u bazi

    public $timestamps = true;

    /*public function recept()
    {
        return $this->belongsTo(Recept::class, 'recept_id');
    }
    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'proizvod_id');
    }*/
    
    
    
}
