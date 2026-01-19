<?php

namespace App\Http\Controllers;

use App\DTO\LoginData;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(Request $request, LoginData $data): Response
    {
        $this->authService->login($data);
        $request->session()->regenerate();

        return response()->noContent();
    }

    public function logout(Request $request): void
    {
        //По заданию реализация не требуется, а жаль :)
    }
}