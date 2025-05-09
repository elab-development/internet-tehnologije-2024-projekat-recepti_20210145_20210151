<?php

namespace App\Http\Controllers;

use App\Models\Proizvod;
use App\Models\ProizvodRecept;
use App\Models\Korpa;
use App\Models\Kupovina;
use Illuminate\Http\Request;
use App\Http\Resources\ProizvodKorpaResource;
use App\Http\Resources\ProizvodKupovinaResource;
use App\Http\Resources\ProizvodReceptResource;
use App\Models\Recept;
use App\Http\Resources\ProizvodResource;
use App\Http\Resources\KorpaResource;
use Validator;
use App\Http\Resources\KupovinaResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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

    // Kreiranje novog proizvoda u bazi
    $proizvod = Proizvod::create([
        'naziv' => $validated['naziv'],
        'kategorija' => $validated['kategorija'],
        'cena' => $validated['cena'],
        'dostupna_kolicina' => $validated['dostupna_kolicina'],
        'tip' => $validated['tip'],
        'slika' => null, // Privremeno NULL
    ]);

    // Generisanje naziva slike i putanje
    $nazivSlike = Str::slug($validated['naziv']) . '.jpeg';
    $putanjaSlike = 'proizvodi_image/' . $nazivSlike;

    // Provera da li slika postoji u storage direktorijumu
    if (Storage::disk('public')->exists($putanjaSlike)) {
        $proizvod->slika = asset('storage/' . $putanjaSlike); // Generisanje URL-a za sliku
    } else {
        $proizvod->slika = asset('storage/proizvodi_image/default.jpg'); // Podrazumevana slika
    }

        // Cuvanje ispravne putanje slike u bazi
        $proizvod->save();

        return response()->json([
            'message' => 'Proizvod je uspešno dodat.',
            'proizvod' => $proizvod
        ], 201);
    }


    public function getAllProducts(Request $request)
    {
        // Parametar 'per_page' omogucava korisnicima da biraju broj proizvoda po stranici
        $perPage = $request->input('per_page', 10); // Ako nije prosleđen, koristi se podrazumevani broj proizvoda po stranici (10)

        // Paginacija proizvoda sa zeljenim brojem proizvoda po stranici
        $proizvodi = Proizvod::paginate($perPage);

        // Provera da li su proizvodi prazni
        if ($proizvodi->isEmpty()) {
            return response()->json(['message' => 'Nema dostupnih proizvoda.'], 404);
        }

        // Dodaje punu URL putanju slike za svaki proizvod
        foreach ($proizvodi as $proizvod) {
            if (!$proizvod->slika) {
                // Ako proizvod nema sliku, poveži ga sa slikom na osnovu naziva proizvoda
                $proizvod->slika = asset('storage/proizvodi_image/' . Str::slug($proizvod->naziv) . '.jpeg');
            }
        }

        // Vracanje proizvoda zajedno sa informacijama o paginaciji
        return response()->json([
            'message' => 'Lista svih proizvoda.',
            'data' => $proizvodi->items(),  // Vraca samo proizvode za trenutnu stranicu
            'pagination' => [
                'current_page' => $proizvodi->currentPage(),
                'last_page' => $proizvodi->lastPage(),
                'total' => $proizvodi->total(),
                'per_page' => $proizvodi->perPage(),
            ]
        ]);
    }

    //Pretraga proizvoda
    public function search(Request $request)
{
    // Preuzimanje parametara za pretragu iz zahteva
    $kljucnaRec = $request->input('keyword'); 
    $kategorija = $request->input('kategorija'); 
    $tip = $request->input('tip');  // Tip proizvoda (organski ili neorganski)
    $cena_min = $request->input('cena_min');
    $cena_max = $request->input('cena_max'); 
    $dostupna_kolicina = $request->input('dostupna_kolicina');

    // Upit za pretragu
    $query = Proizvod::query();

    if ($kljucnaRec) {
        $query->where('naziv', 'like', "%{$kljucnaRec}%");
    }
    if ($kategorija) {
        $query->where('kategorija', $kategorija);
    }
    if ($tip) {
        $query->where('tip', $tip);
    }
    if ($cena_min !== null && is_numeric($cena_min)) {
        $query->where('cena', '>=', $cena_min);
    }
    if ($cena_max !== null && is_numeric($cena_max)) {
        $query->where('cena', '<=', $cena_max);
    }

    // Ako je dostupnost definisana, dodajemo uslov da proizvod ima kolicinu veću od 0
    if ($dostupna_kolicina !== null) {
        $query->where('dostupna_kolicina', '>', 0);
    }

    // Rezultat - paginacija 10 proizvoda po str
    $proizvodi = $query->paginate(10);

    // Provera da li su proizvodi pronađeni
    if ($proizvodi->isEmpty()) {
        return response()->json(['message' => 'Nema proizvoda koji odgovaraju kriterijumima pretrage.'], 404);
    }

    // Dodavanje slike ako nije postavljena
    foreach ($proizvodi as $proizvod) {
        if (!$proizvod->slika) {
            $proizvod->slika = asset('storage/proizvodi_image/' . $proizvod->naziv . '.jpeg');
        }
    }
    // Vracanje podataka o proizvodima i paginaciji
    return response()->json([
        'message' => 'Sistem je pronašao proizvode.',
        'data' => $proizvodi->items(), 
        'pagination' => [
            'total' => $proizvodi->total(), // Ukupan broj proizvoda
            'current_page' => $proizvodi->currentPage(), // Trenutna stranica
            'last_page' => $proizvodi->lastPage(), // Poslednja stranica
            'per_page' => $proizvodi->perPage(), // Broj proizvoda po stranici
            'from' => $proizvodi->firstItem(), // Prvi proizvod na trenutnoj stranici
            'to' => $proizvodi->lastItem(), // Poslednji proizvod na trenutnoj stranici
        ]
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
        // Trazi proizvod sa id
        $proizvod = Proizvod::findOrFail($id);
        $proizvod->load('recepti'); // Ucitava povezane recepte

        if ($proizvod->recepti->isEmpty()) {
            return response()->json(['message' => 'Nema povezanih recepata za ovaj proizvod.'], 404);
        }

        // Vraca proizvod sa povezanim receptima kroz resurs
        return new ProizvodResource($proizvod);
    }
    public function showProizvodKorpa($id)
    {
        $korpa = Korpa::findOrFail($id);
        $korpa->load('proizvodi'); // Ucitava povezane proizvode
        // Vraca korpu sa svim povezanim proizvodima
        return new KorpaResource($korpa);
    }
    public function showProizvodKupovina($id)
    {
        $proizvod = Proizvod::findOrFail($id);
    // Ucitava povezane kupovine kroz pivot tabelu 
        $proizvod->load('kupovine');  

    // Vraca proizvod sa svim povezanim kupovinama
        return new ProizvodKupovinaResource($proizvod->kupovine);
    }
}
