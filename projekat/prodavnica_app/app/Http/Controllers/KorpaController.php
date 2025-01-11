<?php

namespace App\Http\Controllers;

use App\Models\Korpa;
use App\Models\Proizvod;
use Illuminate\Http\Request;



class KorpaController extends Controller
{
    public function addProduct(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Morate biti prijavljeni da biste koristili korpu.'], 401);
        }
    
        $validated = $request->validate([
            'proizvod_id' => 'required|exists:proizvods,id',
            'kolicina' => 'required|integer|min:1',
        ]);

        $korpa = auth()->user()->korpa;
        if (!$korpa) {
            $korpa = Korpa::create(['user_id' => auth()->id()]);
        }

        $proizvod = Proizvod::find($validated['proizvod_id']);
        //Dodavanje u pivot tabelu
        $korpa->proizvodi()->syncWithoutDetaching([
            $proizvod->id => ['kolicina_proizvoda' => $validated['kolicina']],
        ]);

        $ukupnaCena = $korpa->ukupna_cena + ($proizvod->cena * $validated['kolicina']);
        $korpa->update(['ukupna_cena' => $ukupnaCena]);

        return response()->json(['message' => 'Proizvod dodat u korpu.']);
    }

    public function removeProduct(Request $request)
    {
        $validated = $request->validate([
            'proizvod_id' => 'required|exists:proizvods,id',
        ]);

        $korpa = auth()->user()->korpa;
        if ($korpa) {
            $korpa->proizvodi()->detach($validated['proizvod_id']);
        }

        return response()->json(['message' => 'Proizvod uklonjen iz korpe.']);
    }

    public function viewCart()
    {
        $korpa = auth()->user()->korpa;
        if (!$korpa) {
            return response()->json(['message' => 'Korpa je prazna.']);
        }

        return response()->json($korpa->load('proizvodi'));
    }
}
