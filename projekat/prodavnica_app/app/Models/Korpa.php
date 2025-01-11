<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Korpa extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ukupna_cena', 'status'];

    public function proizvodi()
    {
        return $this->belongsToMany(Proizvod::class, 'proizvod_korpas')
                    ->withPivot('kolicina_proizvoda')
                    ->withTimestamps();
    }

    public function korisnik()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
