<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralJsonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $credentials  = $request->validated();

        if (!auth()->attempt($credentials)) {
            throw new GeneralJsonException('Неправильный логин и/или пароль', 422);
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response([
            'user' => new UserResource(auth()->user()),
            'token' => $token
        ]);
    }
}
