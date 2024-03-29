<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $credentials = $request->validated();

        $credentials['password'] = bcrypt($request->password);

        $user = User::create($credentials);
        $token = $user->createToken('API Token')->accessToken;

        return response(['user' => $user, 'token' => $token]);
    }
}
