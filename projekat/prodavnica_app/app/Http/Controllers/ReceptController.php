<?php

namespace App\Http\Controllers;

use App\Models\Recept;
use App\Models\Proizvod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ReceptController extends Controller
{

    //radi bez slike
    /*public function store(Request $request)
    {
        // Validacija podataka
        $request->validate([
            'naziv' => 'required|string|max:255',
            'tip_jela' => 'required|string|max:255',
            'vreme_pripreme' => 'required|integer|min:1',
            'opis_pripreme' => 'required|string',
            'proizvodi' => 'required|array',
            'proizvodi.*.proizvod_id' => 'exists:proizvods,id', 
            'proizvodi.*.kolicina' => 'required|numeric|min:0', 
        ]);

        // Kreiranje recepta
        $recept = Recept::create([
            'naziv' => $request->naziv,
            'tip_jela' => $request->tip_jela,
            'vreme_pripreme' => $request->vreme_pripreme,
            'opis_pripreme' => $request->opis_pripreme,
        ]);

        foreach ($request->proizvodi as $proizvod) {
            $recept->proizvodi()->attach($proizvod['proizvod_id'], ['kolicina' => $proizvod['kolicina']]);
        }

        // Povratni odgovor
        return response()->json([
            'message' => 'Recept uspešno kreiran!',
            'recept' => $recept
        ], 201);
    }*/

    public function store(Request $request)
{
    // Validacija podataka
    $validated = $request->validate([
        'naziv' => 'required|string|max:255',
        'tip_jela' => 'required|string|max:255',
        'vreme_pripreme' => 'required|integer|min:1',
        'opis_pripreme' => 'required|string',
        'proizvodi' => 'required|array',
        'proizvodi.*.proizvod_id' => 'exists:proizvods,id', 
        'proizvodi.*.kolicina' => 'required|numeric|min:0', 
        'proizvodi.*.merna_jedinica' => 'required|string|max:10',
    ]);

    // Kreiranje recepta u bazi
    $recept = Recept::create([
        'naziv' => $validated['naziv'],
        'tip_jela' => $validated['tip_jela'],
        'vreme_pripreme' => $validated['vreme_pripreme'],
        'opis_pripreme' => $validated['opis_pripreme'],
        'slika' => null, // Privremeno NULL
    ]);

    // Generisanje naziva slike i putanje
    $nazivSlike = Str::slug($validated['naziv']) . '.jpeg';
    $putanjaSlike = 'recepti_image/' . $nazivSlike;

    // Provera da li slika postoji u storage direktorijumu
    if (Storage::disk('public')->exists($putanjaSlike)) {
        $recept->slika = asset('storage/' . $putanjaSlike); // Generisanje URL-a za sliku
    } else {
        $recept->slika = asset('storage/recepti_image/default.jpg'); // Podrazumevana slika
    }

    // Čuvanje ispravne putanje slike u bazi
    $recept->save();

    // Dodavanje proizvoda u recept putem pivot tabele 'proizvod_recept'
    foreach ($request->proizvodi as $proizvod) {
        $recept->proizvodi()->attach($proizvod['proizvod_id'], [
            'kolicina' => $proizvod['kolicina'],
            'merna_jedinica' => $proizvod['merna_jedinica']
        ]);
    }

    return response()->json([
        'message' => 'Recept uspešno kreiran!',
        'recept' => $recept
    ], 201);
}


    //Azuriranje recepta
    public function update(Request $request, $id)
    {
        $recept = Recept::findOrFail($id);

        // Validacija podataka
        $validatedData = $request->validate([
            'naziv' => 'nullable|string|max:255',
            'tip_jela' => 'nullable|string|max:255',
            'vreme_pripreme' => 'nullable|integer',
            'opis_pripreme' => 'nullable|string',
            'proizvodi' => 'nullable|array',
        ]);

        // Ažuriranje recepta sa novim podacima
        $recept->update($validatedData);

        //Azuriranje tabele proizvod
        if ($request->has('proizvodi')) {
            foreach ($request->proizvodi as $proizvod) {
                $recept->proizvodi()->updateExistingPivot($proizvod['proizvod_id'], ['kolicina' => $proizvod['kolicina']]);
            }
        }

        // Prikazivanje uspešne poruke
        return response()->json([
            'message' => 'Recept je uspešno izmenjen.',
            'recept' => $recept,
        ]);
    }

    // radi ali ne sa slkom
    /*public function pretraga(Request $request)
    {
    // Validacija parametara filtera
    $validatedData = $request->validate([
        'tip_jela' => 'nullable|string|in:predjelo,glavno jelo,desert,salata',
        'per_page' => 'nullable|integer|min:1', // Dodajemo validaciju za broj stavki po stranici
    ]);

    // Pretraga u bazi sa primenom filtera
    $query = Recept::query();

    // Ako je filter tip jela prisutan, filtriraj rezultate
    if ($request->has('tip_jela')) {
        $query->where('tip_jela', $request->input('tip_jela'));
    }

    // Paginacija (uzimanje parametra 'per_page' iz zahteva, podrazumevano 10)
    $perPage = $request->input('per_page', 10);
    $recepti = $query->paginate($perPage);

    // Ako nema rezultata, vratiti odgovarajuću poruku
    if ($recepti->isEmpty()) {
        return response()->json([
            'message' => 'Nema rezultata za odabrani filter.',
        ], 404);
    }

    // Vraćanje rezultata pretrage sa paginacijom
    return response()->json([
        'recepti' => $recepti->items(),
        'pagination' => [
            'current_page' => $recepti->currentPage(),
            'last_page' => $recepti->lastPage(),
            'total' => $recepti->total(),
            'per_page' => $recepti->perPage(),
        ],
    ]);
    }*/
    public function pretraga(Request $request)
{
    // Validacija parametara filtera
    $validatedData = $request->validate([
        'tip_jela' => 'nullable|string|in:predjelo,glavno jelo,dezert,salata',
        'per_page' => 'nullable|integer|min:1', // Validacija za broj stavki po stranici
    ]);

    // Kreiranje upita za pretragu
    $query = Recept::query();

    // Filtriranje po tipu jela, ako je parametar prisutan
    if ($request->has('tip_jela')) {
        $query->where('tip_jela', $request->input('tip_jela'));
    }

    // Paginacija – uzimanje parametra 'per_page' iz zahteva (podrazumevano 10)
    $perPage = $request->input('per_page', 9);
    $recepti = $query->paginate($perPage);

    // Ako nema rezultata, vratiti poruku o grešci
    if ($recepti->isEmpty()) {
        return response()->json([
            'message' => 'Nema rezultata za odabrani filter.',
        ], 404);
    }

    // Vraćanje rezultata pretrage sa paginacijom
    return response()->json([
        'recepti' => $recepti->items(),
        'pagination' => [
            'current_page' => $recepti->currentPage(),
            'last_page' => $recepti->lastPage(),
            'total' => $recepti->total(),
            'per_page' => $recepti->perPage(),
        ],
    ]);
}



//Ucitavanje pojedinacnih recepata
    public function show($id)
    {
    $recept = Recept::with('proizvodi')->findOrFail($id);
    //dd($recept->toArray());

    if (!$recept) {
        return response()->json(['message' => 'Recept nije pronađen'], 404);
    }

    return response()->json($recept);
    }


    



}
