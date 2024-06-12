<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        $token = $user->createToken('Laravel Personal Access Client')->accessToken;

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }
}
