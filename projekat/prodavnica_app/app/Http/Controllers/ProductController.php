<?php

namespace App\Http\Controllers;

use App\Models\Proizvod;
use Illuminate\Http\Request;
use App\Http\Resources\ProizvodKorpaResource;
use App\Http\Resources\ProizvodKupovinaResource;
use App\Http\Resources\ProizvodReceptResource;
use App\Models\Recept;
use Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validacija podataka
        $validated = $request->validate([
            'naziv' => 'required|string|max:255',
            'kategorija' => 'required|string|max:255',
            'cena' => 'required|numeric|min:0',
            'dostupna_kolicina' => 'required|integer|min:1',
            'tip' => 'required|string|in:organski,neorganski',
        ]);

        // Kreiranje novog proizvoda
        $proizvod = Proizvod::create([
            'naziv' => $validated['naziv'],
            'kategorija' => $validated['kategorija'],
            'cena' => $validated['cena'],
            'dostupna_kolicina' => $validated['dostupna_kolicina'],
            'tip' => $validated['tip'],
        ]);

        return response()->json([
            'message' => 'Proizvod je uspešno dodat.',
            'proizvod' => $proizvod
        ], 201);
    }


    //Pretraga proizvoda
    public function search(Request $request)
    {
    // Preuzimanje parametara za pretragu iz zahteva
    $kljucnaRec = $request->input('keyword'); 
    $kategorija = $request->input('kategorija'); 
    $cena_min = $request->input('cena_min');
    $cena_max = $request->input('cena_max'); 
    $dostupna_kolicina = $request->input('dostupna_kolicina');

    // Upit za pretragu
    $query = Proizvod::query();

    // Pretraga na osnovu kljucne reci
    if ($kljucnaRec) {
        $query->where('naziv', 'like', "%{$kljucnaRec}%");
    }

    // Pretraga na osnovu kategorije
    if ($kategorija) {
        $query->where('kategorija', $kategorija);
    }

    // Pretraga na osnovu min cene
    if ($cena_min !== null) {
        $query->where('cena', '>=', $cena_min);
    }

    // Pretraga na osnovu max cene
    if ($cena_max !== null) {
        $query->where('cena', '<=', $cena_max);
    }

    // Ako je dostupnost definisana, dodajemo uslov da proizvod ima kolicinu veću od 0
    /*if ($dostupnost !== null) {
        $query->where('kolicina', '>', 0);
    }*/
    if ($dostupna_kolicina !== null) {
        $query->where('dostupna_kolicina', '>', 0);
    }

    // Rezultat - paginacija 10 proizvoda po str
    $proizvodi = $query->paginate(10);


    if ($proizvodi->isEmpty()) {
        return response()->json(['message' => 'Nema proizvoda koji odgovaraju kriterijumima pretrage.'], 404);
    }

    return response()->json([
        'message' => 'Sistem je pronašao proizvode.',
        'data' => $proizvodi
    ]);
    }

    //Azuriranje proizvoda
    public function update(Request $request, $id)
    {
    try {
        //Pronalazenje proizvoda u bazi na osnovu id
        $proizvod = Proizvod::find($id);

        if (!$proizvod) {
            //Ako proizvod nije pronadjen, poruka o gresci
            return response()->json(['message' => 'Proizvod nije pronađen.'], 404);
        }

        //Azuriranje podataka proizvoda
        $proizvod->naziv = $request->input('naziv');
        $proizvod->kategorija = $request->input('kategorija');
        $proizvod->cena = $request->input('cena');
        $proizvod->dostupna_kolicina = $request->input('dostupna_kolicina');

        //Cuvanje izmena u bazi
        $proizvod->save();

        //Ako je uspesno, poruka o uspesnosti
        return response()->json(['message' => 'Proizvod je uspešno izmenjen.']);
        } catch (\Exception $e) {
        //Ako nije, poruka o gresci
        return response()->json([
            'message' => 'Došlo je do greške prilikom izmene proizvoda.',
            'error' => $e->getMessage() 
        ], 500);
        }
    }
    public function showProizvodRecept($id)
    {
        // Nadji proizvod sa id
        $proizvod = Proizvod::findOrFail($id);
        $proizvod->load('recepti');

        if ($proizvod->recepti->isEmpty()) {
            return response()->json(['message' => 'Nema povezanih recepata za ovaj proizvod.'], 404);
        }
        // Vratiti sve povezane recepte putem pivot tabele
        return ProizvodReceptResource::collection($proizvod->recepti); 
    }
    public function showProizvodKorpa($id)
    {
        // Nadji proizvod sa id
        $korpa = Korpa::findOrFail($id); // Ovdje koristimo Korpa model, jer nam treba korpa
     
        // Vratiti sve povezane proizvode putem pivot tabele
        return ProizvodKorpaResource::collection($korpa->proizvodi); 
        // Pretpostavlja se da je 'proizvodi' relacija koja je definisana u modelu Korpa
    }
    public function showProizvodKupovina($id)
    {
        // Nadji proizvod sa id
        $kupovina = Kupovina::findOrFail($id); // Ovdje koristimo Kupovina model, jer nam treba kupovina
     
        // Vratiti sve povezane proizvode putem pivot tabele
        return ProizvodKupovinaResource::collection($kupovina->proizvodi);
        // Pretpostavlja se da je 'proizvodi' relacija koja je definisana u modelu Kupovina
    }
}
