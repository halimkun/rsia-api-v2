<?php

namespace App\Http\Controllers\v2;

use App\Helpers\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\RsiaOtp;

class RsiaOtpController extends Controller
{
    /**
     * Create a new OTP.
     */
    public function createOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string|max:100',
            'nik'    => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        // Generate 6-digit OTP
        $otpCode = random_int(100000, 999999);

        // Create OTP
        $otp = RsiaOtp::createOtp([
            'app_id'     => $request->app_id,
            'nik'        => $request->nik,
            'otp'        => $otpCode,
            'expired_at' => now()->addHour(),
        ]);

        // Return OTP for demonstration purposes (in production, send it via SMS/email)
        return ApiResponse::successWithData(['otp_code' => $otpCode, 'expired_at' => $otp->expired_at], "OTP created successfully");
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string|max:100',
            'nik'    => 'required|string|max:50',
            'otp'    => 'required|string|max:6',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        // Get the OTP record
        $otp = RsiaOtp::where('app_id', $request->app_id)
            ->where('nik', $request->nik)
            ->first();

        if (!$otp) {
            return ApiResponse::notFound("OTP not found or invalid");
        }

        // Check if the OTP is valid
        if ($otp->isValidOtp($request->otp)) {
            $otp->update(['is_used' => true]);
            return ApiResponse::success("OTP verified successfully");
        }

        return ApiResponse::error("Invalid OTP or expired", "invalid_otp", null, 422);
    }

    /**
     * Invalidate all expired OTPs (optional, for cleanup).
     */
    public function invalidateExpiredOtps()
    {
        RsiaOtp::where('expired_at', '<', now())->update(['is_used' => true]);
        return ApiResponse::success("Expired OTPs invalidated");
    }

    /**
     * Resend OTP (if needed).
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string|max:100',
            'nik'    => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        // Find existing OTP
        $otp = RsiaOtp::where('app_id', $request->app_id)
            ->where('nik', $request->nik)
            ->where('is_used', false)
            ->first();

        if ($otp && $otp->expired_at > now()) {
            return ApiResponse::error("An OTP is already active", "otp_active", null, 422);
        }

        // Generate a new OTP
        return $this->createOtp($request);
    }
}
