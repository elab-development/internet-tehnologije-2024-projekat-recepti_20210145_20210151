<?php

namespace App\Http\Controllers;

use App\Models\Korpa;
use App\Models\Proizvod;
use Illuminate\Http\Request;
use App\Models\ProizvodKorpa;
use Illuminate\Support\Facades\Auth;



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

    // Dohvatanje korpe korisnika
    $korpa = auth()->user()->korpa;
    if (!$korpa) {
        // Ako korisnik nema korpu, kreiraj novu
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

    // Vraćanje korpa_id u odgovoru
    return response()->json([
        'message' => 'Proizvod dodat u korpu.',
        'korpa_id' => $korpa->id // Dodaj korpa_id u odgovor
    ]);
}


    public function removeProduct(Request $request)
    {
        $validated = $request->validate([
            'proizvod_id' => 'required|exists:proizvods,id',
        ]);
    
        $korpa = auth()->user()->korpa;
    
        if ($korpa) {
            // Trazi proizvod u pivot tabeli
            $proizvod = $korpa->proizvodi()->where('proizvod_id', $validated['proizvod_id'])->first();
    
            if ($proizvod) {
                // Racuna novu cenu nakon uklanjanja proizvoda
                $novaCena = $korpa->ukupna_cena - ($proizvod->pivot->kolicina_proizvoda * $proizvod->cena);
    
                // Azurira ukupnu cenu korpe
                $korpa->update(['ukupna_cena' => max($novaCena, 0)]);
    
                // Uklanja proizvod iz pivot tabele
                $korpa->proizvodi()->detach($validated['proizvod_id']);
                return response()->json(['message' => 'Proizvod uspešno uklonjen']);
            }
        }
    
        // Proverava da li je korpa sada prazna i uklanja ako jeste
        if ($korpa && $korpa->proizvodi()->count() === 0) {
            $korpa->delete();
        }

        return response()->json(['error' => 'Proizvod nije pronađen u korpi'], 400);
    }

    public function viewCart()
    {
        $korpa = auth()->user()->korpa;
        if (!$korpa) {
            return response()->json(['message' => 'Korpa je prazna.']);
        }
        $korpa->load(['proizvodi' => function ($query) {
            $query->withPivot('kolicina_proizvoda'); // Ucitaj kolicinu proizvoda iz pivot tabele
        }]);
        return response()->json($korpa);
    }
    
    //Azuriranje kolicine proizvoda u korpi
        public function updateProduct(Request $request)
    {
        $request->validate([
            'proizvod_id' => 'required|exists:proizvods,id',
            'kolicina_proizvoda' => 'required|integer|min:0',  // Omoguceno postavljanje kolicine na 0
            'korpa_id' => 'required|exists:korpas,id', 
        ]);

        $korpa = Korpa::find($request->korpa_id);

        if (!$korpa) {
            return response()->json(['message' => 'Korpa ne postoji.'], 404);
        }

        $proizvodKorpa = ProizvodKorpa::where('korpa_id', $korpa->id)
                                ->where('proizvod_id', $request->proizvod_id)
                                ->first();

        if (!$proizvodKorpa) {
            return response()->json(['message' => 'Proizvod nije u korpi.'], 404);
        }

        // Ako je kolicina 0, uklanjamo proizvod iz korpe
        /*if ($request->kolicina_proizvoda == 0) {
            $proizvodKorpa->delete();
            $this->updateTotalPrice($korpa);
            return response()->json(['message' => 'Proizvod je uklonjen iz korpe.']);
        }*/

        $korpa->proizvodi()->updateExistingPivot($request->proizvod_id, [
            'kolicina_proizvoda' => $request->kolicina_proizvoda
        ]);

        $this->updateTotalPrice($korpa);
        // Vrati azuriranu korpu i proizvod
        $korpa = Korpa::with('proizvodi')->find($korpa->id);


        return response()->json([
            'message' => 'Količina proizvoda uspešno ažurirana.',
            'proizvod' => $proizvodKorpa,
            'korpa' => $korpa->proizvodi,
        ]);
    }
    private function updateTotalPrice($korpa)
    {
        $totalPrice = 0;

        // Racunanje ukupne cene na osnovu proizvoda i njihove količine
        foreach ($korpa->proizvodi as $product) {
            $totalPrice += $product->pivot->kolicina_proizvoda * $product->cena;
        }

        // Azuriraj ukupnu cenu u bazi
        $korpa->update(['ukupna_cena' => $totalPrice]);
    }

    public function clear(Request $request)
    {
        $korpa = Korpa::where('user_id', auth()->id())->latest()->first(); // Pronalazi najnoviju korpu korisnika

        if ($korpa) {
            $korpa->delete(); // Brise korpu iz baze
        }

        return response()->json([
            'message' => 'Kupovina uspešno završena. Vaša korpa je sada prazna.'
        ]);
    }

}