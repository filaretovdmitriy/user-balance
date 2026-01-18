<?php

use App\DTO\LoginData;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(LoginData $data)
    {
        $ok = Auth::guard('web')->attempt([
            'email' => $data->email,
            'password' => $data->password,
        ]);

        if (!$ok) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }
    }

    public function logout(AuthRequest $request): void
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
