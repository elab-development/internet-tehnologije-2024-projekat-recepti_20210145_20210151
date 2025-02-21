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


class ProductController extends Controller
{

    //Bez slike - radi
    /*public function getAllProducts(Request $request)
{
    // Parametar 'per_page' omogućava korisnicima da biraju broj proizvoda po stranici
    $perPage = $request->input('per_page', 10); // Ako nije prosleđen, koristi se podrazumevani broj proizvoda po stranici (10)

    // Paginacija proizvoda sa željenim brojem proizvoda po stranici
    $proizvodi = Proizvod::paginate($perPage);

    // Provera da li su proizvodi prazni
    if ($proizvodi->isEmpty()) {
        return response()->json(['message' => 'Nema dostupnih proizvoda.'], 404);
    }

    // Vraćanje proizvoda zajedno sa informacijama o paginaciji
    return response()->json([
        'message' => 'Lista svih proizvoda.',
        'data' => $proizvodi->items(),  // Vraća samo proizvode za trenutnu stranicu
        'pagination' => [
            'current_page' => $proizvodi->currentPage(),
            'last_page' => $proizvodi->lastPage(),
            'total' => $proizvodi->total(),
            'per_page' => $proizvodi->perPage(),
        ]
    ]);
}*/

public function getAllProducts(Request $request)
{
    // Parametar 'per_page' omogućava korisnicima da biraju broj proizvoda po stranici
    $perPage = $request->input('per_page', 10); // Ako nije prosleđen, koristi se podrazumevani broj proizvoda po stranici (10)

    // Paginacija proizvoda sa željenim brojem proizvoda po stranici
    $proizvodi = Proizvod::paginate($perPage);

    // Provera da li su proizvodi prazni
    if ($proizvodi->isEmpty()) {
        return response()->json(['message' => 'Nema dostupnih proizvoda.'], 404);
    }

    // Dodaj punu URL putanju slike za svaki proizvod
    foreach ($proizvodi as $proizvod) {
        if (!$proizvod->slika) {
            // Ako proizvod nema sliku, poveži ga sa slikom na osnovu naziva proizvoda
            $proizvod->slika = asset('storage/proizvodi_image/' . $proizvod->naziv . '.jpeg'); // Pretpostavljamo da je slika u formatu .jpg
        }
    }

    // Vraćanje proizvoda zajedno sa informacijama o paginaciji
    return response()->json([
        'message' => 'Lista svih proizvoda.',
        'data' => $proizvodi->items(),  // Vraća samo proizvode za trenutnu stranicu
        'pagination' => [
            'current_page' => $proizvodi->currentPage(),
            'last_page' => $proizvodi->lastPage(),
            'total' => $proizvodi->total(),
            'per_page' => $proizvodi->perPage(),
        ]
    ]);
}


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
        'slika' => null, // Slika je opcionalna pri unosu, ali ćemo je povezati kasnije
    ]);

    // Povezivanje slike sa proizvodom prema imenu proizvoda
    $proizvod->slika = asset('storage/proizvodi_image/' . $proizvod->naziv . '.jpg'); // Pretpostavljamo da je slika u formatu .jpg
    $proizvod->save(); // Snima promenjeni proizvod sa URL-om za sliku

    return response()->json([
        'message' => 'Proizvod je uspešno dodat.',
        'proizvod' => $proizvod
    ], 201);
}



/*public function store(Request $request)
{
    // Validacija podataka
    $validated = $request->validate([
        'naziv' => 'required|string|max:255',
        'kategorija' => 'required|string|max:255',
        'cena' => 'required|numeric|min:0',
        'dostupna_kolicina' => 'required|integer|min:1',
        'tip' => 'required|string|in:organski,neorganski',
        'slika' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Dodata validacija slike
    ]);

    // Provera i čuvanje slike
    if ($request->hasFile('slika')) {
        $slika = $request->file('slika');
        $putanja = $slika->store('proizvodi_image', 'public'); // Snima u storage/app/public/proizvodi_image
    } else {
        $putanja = null;
    }

    // Kreiranje novog proizvoda
    $proizvod = Proizvod::create([
        'naziv' => $validated['naziv'],
        'kategorija' => $validated['kategorija'],
        'cena' => $validated['cena'],
        'dostupna_kolicina' => $validated['dostupna_kolicina'],
        'tip' => $validated['tip'],
        'slika' => $putanja, // Čuva putanju slike u bazi
    ]);

    // Dodavanje punog URL-a za sliku
    if ($proizvod->slika) {
        $proizvod->slika = asset('storage/' . $proizvod->slika); // Generiše punu URL putanju
    }

    return response()->json([
        'message' => 'Proizvod je uspešno dodat.',
        'proizvod' => $proizvod
    ], 201);
}
*/


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
    if ($dostupna_kolicina !== null) {
        $query->where('dostupna_kolicina', '>', 0);
    }

    // Rezultat - paginacija 10 proizvoda po str
    $proizvodi = $query->paginate(10);

    // Provera da li su proizvodi pronađeni
    if ($proizvodi->isEmpty()) {
        return response()->json(['message' => 'Nema proizvoda koji odgovaraju kriterijumima pretrage.'], 404);
    }

    // Vraćanje podataka o proizvodima i paginaciji
    return response()->json([
        'message' => 'Sistem je pronašao proizvode.',
        'data' => $proizvodi->items(), // Vraćamo samo proizvode
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
        // Nađi proizvod sa id
        $proizvod = Proizvod::findOrFail($id);
        $proizvod->load('recepti'); // Učitaj povezane recepte

        if ($proizvod->recepti->isEmpty()) {
            return response()->json(['message' => 'Nema povezanih recepata za ovaj proizvod.'], 404);
        }

        // Vratiti proizvod sa povezanim receptima kroz resurs
        return new ProizvodResource($proizvod);
    }

    public function showProizvodKorpa($id)
    {
        $korpa = Korpa::findOrFail($id);
        $korpa->load('proizvodi'); // Učitaj povezane proizvode
        // Vratiti korpu sa svim povezanim proizvodima
        return new KorpaResource($korpa);
    }
    public function showProizvodKupovina($id)
    {
        $proizvod = Proizvod::findOrFail($id);

    // Učitajte povezane kupovine kroz pivot tabelu (pretpostavljam da imate ovu vezu)
        $proizvod->load('kupovine');  // Ovde pretpostavljamo da postoji 'kupovine' relacija u modelu Proizvod

    // Vratiti proizvod sa svim povezanim kupovinama
        return new ProizvodKupovinaResource($proizvod->kupovine);

    }
}
