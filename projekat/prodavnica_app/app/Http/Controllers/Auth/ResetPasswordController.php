<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Slanje linka za resetovanje lozinke
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $response = Password::sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link za resetovanje lozinke je poslat na vaš email.'])
            : response()->json(['message' => 'Neuspešno slanje linka za resetovanje lozinke.'], 500);
    }

    // Resetovanje lozinke
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required',
        ]);

        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
            }
        );

        return $response === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Vaša lozinka je uspešno resetovana.'])
            : response()->json(['message' => 'Neuspešan pokušaj resetovanja lozinke.'], 500);
    }
}
