<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\VerifyCodeRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

class VerifyCodeController extends Controller
{

    /**
     * Handle the incoming request to verify the admin's code.
     *
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     */
    public function __invoke(VerifyCodeRequest $request): JsonResponse
    {
        // Find the admin by the provided identifier and type (username or mobile number).
        $admin = $this->findAdminByIdentifier($request->identifier, $request->type);

        // If the admin is not found, return a 404 response.
        if (!$admin) {
            return response()->json(['message' => 'Admin with this identifier not found'], 404);
        }

        // If the verification code is invalid, return a 400 response.
        if ($this->isInvalidVerificationCode($admin, $request->code)) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        // Verify the admin and update their status.
        $this->verifyAdmin($admin);

        // Return a success response.
        return response()->json(['message' => 'Verification successful'], 200);
    }



    /**
     * Find a admin by their identifier (username or mobile number).
     *
     * @param string $identifier
     * @param string $type
     * @return Admin|null
     */
    private function findAdminByIdentifier(string $identifier, string $type): ?Admin
    {
        // Search for the admin based on the type (username or mobile number).
        return Admin::where($type === 'username' ? 'username' : 'mobile_number', $identifier)->first();
    }



    /**
     * Check if the provided verification code is invalid.
     *
     * @param Admin $admin
     * @param string $code
     * @return bool
     */
    private function isInvalidVerificationCode(Admin $admin, string $code): bool
    {
        // Return true if the verification code does not match.
        return $admin->verification_code !== $code;
    }



    /**
     * Verify the admin by clearing the verification code and setting their status as verified.
     *
     * @param Admin $admin
     * @return void
     */
    private function verifyAdmin(Admin $admin): void
    {
        // Update the admin to clear the verification code and set them as verified.
        $admin->update([
            'verification_code' => null,
            'is_verified' => true,
        ]);
    }
}
