<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        // Try to find the admin by username, or mobile number
        $admin = Admin::where('username', $request->identifier)
            ->orWhere('mobile_number', $request->identifier)
            ->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('Personal Access Token')->accessToken;

            data_set($admin, 'access_token', $token);

            return AdminResource::make($admin);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
