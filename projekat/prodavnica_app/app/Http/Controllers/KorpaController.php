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
        
        // Proveravamo dostupnu količinu proizvoda
        if ($proizvod->dostupna_kolicina < $validated['kolicina']) {
            return response()->json(['message' => 'Proizvoda nema na stanju.'], 400);
        }
    
        // Dodavanje u pivot tabelu
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
            // Pronađi proizvod u pivot tabeli
            $proizvod = $korpa->proizvodi()->where('proizvod_id', $validated['proizvod_id'])->first();
    
            if ($proizvod) {
                // Izračunaj novu cenu nakon uklanjanja proizvoda
                $novaCena = $korpa->ukupna_cena - ($proizvod->pivot->kolicina_proizvoda * $proizvod->cena);
    
                // Ažuriraj ukupnu cenu korpe
                $korpa->update(['ukupna_cena' => max($novaCena, 0)]);
    
                // Ukloni proizvod iz pivot tabele
                $korpa->proizvodi()->detach($validated['proizvod_id']);
            }
        }
    
        // Proveri da li je korpa sada prazna i ukloni je ako jeste
        if ($korpa && $korpa->proizvodi()->count() === 0) {
            $korpa->delete();
        }
    
        return response()->json(['message' => 'Proizvod uklonjen iz korpe.']);
    }

    public function viewCart()
    {
        $korpa = auth()->user()->korpa;
        if (!$korpa) {
            return response()->json(['message' => 'Korpa je prazna.']);
        }
        $korpa->load(['proizvodi' => function ($query) {
            $query->withPivot('kolicina_proizvoda'); // Učitaj količinu proizvoda iz pivot tabele
        }]);
        return response()->json($korpa);
        //return response()->json($korpa->load('proizvodi'));
    }
    
    //Azuriranje kolicine proizvoda u korpi
    public function updateProduct(Request $request)
    {
        $request->validate([
            'proizvod_id' => 'required|exists:proizvods,id',
            'kolicina' => 'required|integer|min:1',
        ]);

        $korpa = Korpa::where('user_id', auth()->id())->first();

        if (!$korpa) {
         return response()->json(['message' => 'Korpa ne postoji.'], 404);
        }

        $proizvodKorpa = ProizvodKorpa::where('korpa_id', $korpa->id)
                                    ->where('proizvod_id', $request->proizvod_id)
                                    ->first();

        if (!$proizvodKorpa) {
            return response()->json(['message' => 'Proizvod nije u korpi.'], 404);
        }

        $proizvodKorpa->kolicina_proizvoda = $request->kolicina;
        $proizvodKorpa->save();

        return response()->json(['message' => 'Količina proizvoda uspešno ažurirana.', 'proizvod' => $proizvodKorpa]);
    }

}
