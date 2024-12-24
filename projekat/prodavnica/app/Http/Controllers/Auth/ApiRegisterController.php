<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validacija podataka
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Kreiranje korisnika
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uloga' => 'korisnik', // Uloga za novog korisnika
        ]);

        // Vraćanje odgovora sa podacima korisnika
        return response()->json([
            'message' => 'Uspešno ste registrovani!',
            'user' => $user
        ], 201);
    }
}
