<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Auth\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        return $this->authService->register($request->all());
    }

    public function login(Request $request)
    {
        return $this->authService->login($request->all());
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
