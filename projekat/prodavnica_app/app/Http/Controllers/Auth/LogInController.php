<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LogInController extends Controller
{
    /*public function login(Request $request)
    {
        // Validacija unetih podataka
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
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
    }*/

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Proverite da li korisnik postoji i da li je lozinka ispravna
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Neispravan email ili lozinka.'], 401);
        }

        // Generišemo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Uspešno ste se prijavili.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        // Prvo proveravamo da li korisnik ima validan token
        $user = Auth::user();

        // Revoke (povuci) API token
        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });

            // Uspešno odjavljivanje
            return response()->json([
                'message' => 'Uspešno ste se odjavili.'
            ]);
        }

        // Ako korisnik nije prijavljen
        return response()->json([
            'message' => 'Korisnik nije prijavljen.'
        ], 401);
    }
}
