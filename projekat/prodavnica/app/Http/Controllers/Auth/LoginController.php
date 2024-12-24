<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validacija unetih podataka
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        // Pronađi korisnika po emailu
        $user = User::where('email', $validatedData['email'])->first();

        // Provera validnosti unetih podataka
        if ($user && Hash::check($validatedData['password'], $user->password)) {
            // Generišemo API token za korisnika (ako koristimo Passport ili Sanctum)
            $token = $user->createToken('AppToken')->plainTextToken;

            // Uspešna prijava, vraćamo token i poruku o uspehu
            return response()->json([
                'message' => 'Uspešno ste prijavljeni!',
                'token' => $token
            ]);
        } else {
            // Ako podaci nisu ispravni
            return response()->json([
                'message' => 'Pogrešni email ili lozinka.',
            ], 401);
        }
    }
}
