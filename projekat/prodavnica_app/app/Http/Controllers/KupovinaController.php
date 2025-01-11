<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kupovina;
use Illuminate\Http\Request;

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
        ]);

        $kupovina = Kupovina::create([
            'id_user' => auth()->user()->id, // Automatski povezujemo sa trenutno prijavljenim korisnikom
            'ukupna_cena' => $request->ukupna_cena,
            'nacin_placanja' => $request->nacin_placanja,
            'adresa_dostave' => $request->adresa_dostave,
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
        
        // Validacija i ažuriranje
        $request->validate([
            //'id_user' => 'required|exists:users,id',
            'ukupna_cena' => 'required|numeric',
            'nacin_placanja' => 'required|string',
            'adresa_dostave' => 'required|string',
        ]);
        
        $kupovina->update($request->all());

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
