<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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
}
