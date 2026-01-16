<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $ok = Auth::guard('web')->attempt([
            'email' => $credentials['login'],
            'password' => $credentials['password'],
        ]);

        if (! $ok) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        $request->session()->regenerate();

        return response()->noContent();
    }

    public function logout(Request $request)
    {
    }
}
