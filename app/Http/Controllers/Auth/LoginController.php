<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $credentials  = $request->validated();

        if (!auth()->attempt($credentials)) {
            return response(['error_message' => 'Некорректные данные. Пожалуйста, попробуйте еще.']);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);
    }
}
