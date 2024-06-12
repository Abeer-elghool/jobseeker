<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\VerifyCodeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerifyCodeController extends Controller
{

    /**
     * Handle the incoming request to verify the user's code.
     *
     * @param VerifyCodeRequest $request
     * @return JsonResponse
     */
    public function __invoke(VerifyCodeRequest $request): JsonResponse
    {
        // Find the user by the provided identifier and type (username or mobile number).
        $user = $this->findUserByIdentifier($request->identifier, $request->type);

        // If the user is not found, return a 404 response.
        if (!$user) {
            return response()->json(['message' => 'User with this identifier not found'], 404);
        }

        // If the verification code is invalid, return a 400 response.
        if ($this->isInvalidVerificationCode($user, $request->code)) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        // Verify the user and update their status.
        $this->verifyUser($user);

        // Return a success response.
        return response()->json(['message' => 'Verification successful'], 200);
    }



    /**
     * Find a user by their identifier (username or mobile number).
     *
     * @param string $identifier
     * @param string $type
     * @return User|null
     */
    private function findUserByIdentifier(string $identifier, string $type): ?User
    {
        // Search for the user based on the type (username or mobile number).
        return User::where($type === 'username' ? 'username' : 'mobile_number', $identifier)->first();
    }



    /**
     * Check if the provided verification code is invalid.
     *
     * @param User $user
     * @param string $code
     * @return bool
     */
    private function isInvalidVerificationCode(User $user, string $code): bool
    {
        // Return true if the verification code does not match.
        return $user->verification_code !== $code;
    }



    /**
     * Verify the user by clearing the verification code and setting their status as verified.
     *
     * @param User $user
     * @return void
     */
    private function verifyUser(User $user): void
    {
        // Update the user to clear the verification code and set them as verified.
        $user->update([
            'verification_code' => null,
            'is_verified' => true,
        ]);
    }
}
