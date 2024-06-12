<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\RegisterRequest;
use App\Models\Admin;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $admin = Admin::create($request->validated());

        $token = $admin->createToken('Laravel Personal Access Client')->accessToken;

        return response()->json(['message' => 'Admin registered successfully', 'token' => $token], 201);
    }
}
