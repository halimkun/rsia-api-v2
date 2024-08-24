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
            'app_id' => 'required|exists:oauth_clients,id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $client = \DB::table('oauth_clients')->where('id', $request->app_id)->first();

        if ($client->revoked) {
            return ApiResponse::error("Client revoked", "client_revoked", null, 422);
        }

        if ($client->password_client) {
            return ApiResponse::error("Client is confidential", "client_confidential", null, 422);
        }

        $petugas = \App\Models\Petugas::where('nip', $request->user()->id_user)->first();

        if (!$petugas) {
            return ApiResponse::error("Petugas not found", "petugas_not_found", null, 404);
        }

        // Generate 6-digit OTP
        $otpCode = random_int(100000, 999999);
        $message = "Ini adalah kode OTP anda dari aplikasi *MESSA* untuk mengakses menu _jasa pelayanan_ (JASPEL) RSIA Aisyiyah Pekajangan, \n*KODE :* `$otpCode` \n\nKode ini hanya berlaku 5 menit, jangan berikan kode ini kepada siapapun.";

        \App\Jobs\SendWhatsApp::dispatch($this->buildNumberWithCountryCode($petugas->no_telp), $message)->onQueue('otp')->delay(now()->addSeconds(random_int(10, 60)));

        // Create OTP
        $otp = RsiaOtp::createOtp([
            'app_id'     => $request->app_id,
            'nik'        => $request->user()->id_user,
            'otp'        => $otpCode,
            'expired_at' => now()->addMinutes(10),
        ]);

        // Return OTP for demonstration purposes (in production, send it via SMS/email)
        return \App\Http\Resources\RealDataResource::make($otp);
    }

    /**
     * Verify the OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|max:100',
            'otp'    => 'required|max:6',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        // Get the OTP record
        $otp = RsiaOtp::where('app_id', $request->app_id)
            ->where('nik', $request->user()->id_user)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otp) {
            return ApiResponse::notFound("OTP invalid");
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
            'app_id' => 'required|exists:oauth_clients,id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        // // Find existing OTP
        // $otp = RsiaOtp::where('app_id', $request->app_id)
        //     ->where('nik', $request->user()->id_user)
        //     ->where('is_used', false)
        //     ->first();

        // if ($otp && $otp->expired_at > now()) {
        //     return ApiResponse::error("An OTP is already active", "otp_active", null, 422);
        // }

        // Generate a new OTP
        return $this->createOtp($request);
    }

    private function buildNumberWithCountryCode($number)
    {
        if (strlen($number) < 10 || strlen($number) > 13) {
            \Log::error("Invalid phone number: " . $number);
            throw new \Exception("Invalid phone number");
        }

        return Str::startsWith($number, '0') ? '62' . substr($number, 1) : $number;
    }
}
