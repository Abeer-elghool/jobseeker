<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\SendVerificationCodeRequest;
use App\Jobs\SendVerificationCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class SendVerificationCodeController extends Controller
{

    /**
     * Handle the incoming request to send a verification code.
     *
     * @param SendVerificationCodeRequest $request
     * @return JsonResponse
     */
    public function __invoke(SendVerificationCodeRequest $request): JsonResponse
    {
        // Find the user by the provided identifier and type (username or mobile number).
        $user = $this->findUserByIdentifier($request->identifier, $request->type);

        // If the user is not found, return a 404 response.
        if (!$user) {
            return response()->json(['message' => 'User with this identifier not found'], 404);
        }

        // If the user is already verified, return a 400 response.
        if ($user->is_verified) {
            return response()->json(['message' => 'User already verified!'], 400);
        }

        // Generate a new verification code.
        $verificationCode = $this->generateVerificationCode();

        // Store the verification code in the database.
        $this->storeVerificationCode($user, $verificationCode);

        // Send the verification code to the user based on the type of the identifier.
        $this->sendVerificationCode($user, $verificationCode, $request->type);

        // Return a success response.
        return response()->json(['message' => 'Verification code sent'], 200);
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
     * Generate a verification code.
     *
     * @return int
     */
    private function generateVerificationCode(): int
    {
        // Logic to generate a verification code (e.g., a random 6-digit number).
        return rand(100000, 999999);
    }



    /**
     * Store the verification code in the database.
     *
     * @param User $user
     * @param int $code
     * @return void
     */
    private function storeVerificationCode(User $user, int $code): void
    {
        // Update the user record with the new verification code.
        $user->update(['verification_code' => $code]);
    }



    /**
     * Send the verification code to the user.
     *
     * @param User $user
     * @param int $code
     * @param string $type
     * @return void
     */
    private function sendVerificationCode(User $user, int $code, string $type): void
    {
        // Send the verification code via email if the identifier is a username.
        if ($type === 'username') {
            SendVerificationCode::dispatch($user->email, $code);
        } else {
            // Send the verification code via SMS if the identifier is a mobile number.
            $this->sendSms($user->mobile_number, $code);
        }
    }



    /**
     * Send an SMS with the verification code.
     *
     * @param string $mobile_number
     * @param int $code
     * @return void
     */
    private function sendSms(string $mobile_number, int $code): void
    {
        //
    }
}
