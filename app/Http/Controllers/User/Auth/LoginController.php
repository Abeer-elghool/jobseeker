<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $credentials = filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ?
            ['email' => $request->identifier, 'password' => $request->password] :
            ['username' => $request->identifier, 'password' => $request->password];

        if (!Auth::attempt($credentials)) {
            $credentials = ['mobile_number' => $request->identifier, 'password' => $request->password];

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        $user = $request->user();
        $token = $user->createToken('Personal Access Token')->accessToken;

        data_set($user, 'access_token', $token);

        return UserResource::make($user);
    }
}
