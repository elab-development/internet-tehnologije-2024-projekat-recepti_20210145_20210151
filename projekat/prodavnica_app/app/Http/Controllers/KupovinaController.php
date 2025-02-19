<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kupovina;
use Illuminate\Http\Request;
use App\Http\Resources\KupovinaResource;

class KupovinaController extends Controller
{
    // Prikazivanje svih kupovina
    public function index()
    {
        $kupovine = Kupovina::all();
        return response()->json($kupovine);
    }

    // Kreiranje nove kupovine
    public function store(Request $request)
    {
        $request->validate([
            'ukupna_cena' => 'required|numeric',
            'nacin_placanja' => 'required|string',
            'adresa_dostave' => 'required|string',
            'broj_kartice' => 'nullable|string', // Ova kolona može biti null, ako nije potrebno
        ]);

        $kupovina = Kupovina::create([
            'id_user' => auth()->user()->id, // Automatski povezujemo sa trenutno prijavljenim korisnikom
            'ukupna_cena' => $request->ukupna_cena,
            'nacin_placanja' => $request->nacin_placanja,
            'adresa_dostave' => $request->adresa_dostave,
            'broj_kartice' => $request->broj_kartice,  // Dodavanje broja kartice
        ]);

        return response()->json($kupovina, 201); // Vraća novokreiranu kupovinu
    }

    // Prikazivanje detalja o jednoj kupovini
    public function show($id)
    {
        $kupovina = Kupovina::findOrFail($id);
        return response()->json($kupovina);
    }

    // Ažuriranje kupovine
    public function update(Request $request, $id)
    {
        $kupovina = Kupovina::findOrFail($id);

    // Validacija podataka (ne uključujemo 'id_user' jer se automatski dodeljuje)
    $request->validate([
        //'ukupna_cena' => 'required|numeric',
        //'nacin_placanja' => 'required|string',
        'adresa_dostave' => 'required|string',
    ]);

    // Dodela ID-a trenutno prijavljenog korisnika
    $kupovina->id_user = auth()->user()->id;  // Automatski postavi ID prijavljenog korisnika

    // Ažuriranje kupovine sa novim podacima
    $kupovina->update([
        'ukupna_cena' => $request->ukupna_cena,
        'nacin_placanja' => $request->nacin_placanja,
        'adresa_dostave' => $request->adresa_dostave,
    ]);

    // Vraćanje ažurirane kupovine
    return response()->json($kupovina);
    }

    // Brisanje kupovine
    public function destroy($id)
    {
        $kupovina = Kupovina::findOrFail($id);
        $kupovina->delete();

        return response()->json(['message' => 'Kupovina obrisana'], 200);
    }
}
