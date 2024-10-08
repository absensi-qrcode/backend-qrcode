<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;

use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request) 
    {
        $data = $this->authService->login($request);
        return $this->success('Berhasil login', $data);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $this->authService->resetPassword($request);
        return $this->success('Berhasil reset password', $data);
    }

    public function logout(Request $request) 
    {
        $this->authService->logout($request);
        return $this->success('User berhasil logout');
    }
}
