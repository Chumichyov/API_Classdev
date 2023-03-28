<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralJsonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $credentials = $request->validated();

        if(!is_null(User::where('email', $credentials['email'])->first())) {
            throw new GeneralJsonException('Пользователь с данной почтой уже существует', 422);

        }

        $credentials['password'] = bcrypt($request->password);

        $user = User::create($credentials);
        $token = $user->createToken('API Token')->accessToken;

        return response(['user' => new UserResource($user), 'token' => $token]);
    }
}
