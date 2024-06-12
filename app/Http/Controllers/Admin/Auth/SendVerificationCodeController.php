<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\SendVerificationCodeRequest;
use App\Jobs\SendVerificationCode;
use App\Models\Admin;
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
        // Find the admin by the provided identifier and type (username or mobile number).
        $admin = $this->findAdminByIdentifier($request->identifier, $request->type);

        // If the admin is not found, return a 404 response.
        if (!$admin) {
            return response()->json(['message' => 'Admin with this identifier not found'], 404);
        }

        // If the admin is already verified, return a 400 response.
        if ($admin->is_verified) {
            return response()->json(['message' => 'Admin already verified!'], 400);
        }

        // Generate a new verification code.
        $verificationCode = $this->generateVerificationCode();

        // Store the verification code in the database.
        $this->storeVerificationCode($admin, $verificationCode);

        // Send the verification code to the admin based on the type of the identifier.
        $this->sendVerificationCode($admin, $verificationCode, $request->type);

        // Return a success response.
        return response()->json(['message' => 'Verification code sent'], 200);
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
     * @param Admin $admin
     * @param int $code
     * @return void
     */
    private function storeVerificationCode(Admin $admin, int $code): void
    {
        // Update the admin record with the new verification code.
        $admin->update(['verification_code' => $code]);
    }



    /**
     * Send the verification code to the admin.
     *
     * @param Admin $admin
     * @param int $code
     * @param string $type
     * @return void
     */
    private function sendVerificationCode(Admin $admin, int $code, string $type): void
    {
        // Send the verification code via email if the identifier is a username.
        if ($type === 'username') {
            SendVerificationCode::dispatch($admin->email, $code);
        } else {
            // Send the verification code via SMS if the identifier is a mobile number.
            $this->sendSms($admin->mobile_number, $code);
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
