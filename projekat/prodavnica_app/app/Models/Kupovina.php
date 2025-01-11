<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kupovina extends Model
{
    use HasFactory;

    protected $table = 'kupovinas'; // Naziv tabele
    protected $primaryKey = 'id_kupovine'; // Primarni ključ

    protected $fillable = [
        'id_user',
        'ukupna_cena',
        'nacin_placanja',
        'adresa_dostave',
    ];

    // Veza sa User modelom (Kupovina pripada korisniku)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Veza sa Proizvod modelom (Mnogi proizvodi mogu pripadati kupovini)
    public function proizvodi()
    {
        return $this->belongsToMany(Proizvod::class, 'proizvod_kupovinas')
                    ->withPivot('kolicina') // Pivot polje za količinu
                    ->withTimestamps(); // Timestamps za pivot tabelu
    }
}
