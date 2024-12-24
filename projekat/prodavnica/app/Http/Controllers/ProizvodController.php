<?php

namespace App\Http\Controllers;

use App\Models\Proizvod;
use Illuminate\Http\Request;

class ProizvodController extends Controller
{
    public function store(Request $request)
    {
        // Validacija podataka
        $validated = $request->validate([
            'naziv' => 'required|string|max:255',
            'kategorija' => 'required|string|max:255',
            'cena' => 'required|numeric|min:0',
            'kolicina' => 'required|integer|min:1',
            'tip' => 'required|string|in:organski,neorganski',
        ]);

        // Kreiranje novog proizvoda
        $proizvod = Proizvod::create([
            'naziv' => $validated['naziv'],
            'kategorija' => $validated['kategorija'],
            'cena' => $validated['cena'],
            'kolicina' => $validated['kolicina'],
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
    //$dostupnost = $request->input('dostupnost');
    $kolicina = $request->input('kolicina');

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
    if ($kolicina !== null) {
        $query->where('kolicina', '>', 0);
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
    /*public function update(Request $request, $id)
    {
    // Pronalazi proizvod na osnovu id
    $proizvod = Proizvod::find($id);

    if (!$proizvod) {
        return response()->json(['message' => 'Proizvod nije pronađen.'], 404);
    }

    // Validacija
    $request->validate([
        'naziv' => 'required|string|max:255',
        'kategorija' => 'required|string',
        'cena' => 'required|numeric|min:0',
        'kolicina' => 'required|integer|min:0'
    ]);

    // Azuriranje
    $proizvod->naziv = $request->input('naziv');
    $proizvod->kategorija = $request->input('kategorija');
    $proizvod->cena = $request->input('cena');
    $proizvod->kolicina = $request->input('kolicina');

    // Cuvanje izmena u bazi
    $proizvod->save();

    return response()->json(['message' => 'Proizvod je uspešno izmenjen.']);
    }*/

    public function update(Request $request, $id)
{
    try {
        // Pronalaženje proizvoda u bazi na osnovu ID-ja
        $proizvod = Proizvod::find($id);

        if (!$proizvod) {
            // Ako proizvod nije pronađen, vraća se poruka o grešci
            return response()->json(['message' => 'Proizvod nije pronađen.'], 404);
        }

        // Ažuriranje podataka proizvoda
        $proizvod->naziv = $request->input('naziv');
        $proizvod->kategorija = $request->input('kategorija');
        $proizvod->cena = $request->input('cena');
        $proizvod->kolicina = $request->input('kolicina');

        // Pokušaj čuvanja izmena u bazi
        $proizvod->save();

        // Ako je uspešno, vraća se poruka o uspehu
        return response()->json(['message' => 'Proizvod je uspešno izmenjen.']);
    } catch (\Exception $e) {
        // Ako se desi greška, vraća se poruka o grešci i detalji greške (opciono)
        return response()->json([
            'message' => 'Došlo je do greške prilikom izmene proizvoda.',
            'error' => $e->getMessage() // Ovo možete ukloniti u produkciji zbog sigurnosti
        ], 500);
    }
}

}
