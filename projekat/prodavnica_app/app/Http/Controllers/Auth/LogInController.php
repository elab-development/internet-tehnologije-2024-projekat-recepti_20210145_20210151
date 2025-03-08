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
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // Proverava da li korisnik postoji i da li je lozinka ispravna
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Neispravan email ili lozinka.'], 401);
        }

        // Generise token
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

            // Uspesno odjavljivanje
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
