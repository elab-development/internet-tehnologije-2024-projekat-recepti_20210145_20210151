<?php

namespace App\Http\Controllers;

use App\Models\Recept;
use App\Models\Proizvod;
use Illuminate\Http\Request;

class ReceptController extends Controller
{
    public function store(Request $request)
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
    public function pretraga(Request $request)
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
