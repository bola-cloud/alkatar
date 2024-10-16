<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function otpSignInPost(Request $request)
    {
      $validated =   $request->validate([
            'phone_number' => 'required',
            'code' => 'required',
        ]);
        $otp = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        $full_phone = $validated['code'] . $validated['phone_number'];
        $phone_without_plus = ltrim($full_phone, '+');
        Otp::create([
            'phone_number' => $full_phone,
            'otp' => $otp,
        ]);

        $response = Http::asForm()->post('https://whatsapi.alsharashoping.com/api/v1/whatsapp/send_otp', [
            'phone_number' => $phone_without_plus,
            'otp' => $otp
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'OTP sent successfully'], 200);
        } else {
            return response()->json(['error' => 'Failed to send OTP'], 500);
        }
    }

    public function otpVerifyPost(Request $request)
    {
       $validated =  $request->validate([
            'phone_number' => 'required',
            'name' => 'required',
            'code' => 'required',
            'otp' => 'required|digits:5',
        ]);

        $phone_number = $validated['phone_number'];
        $name = $validated['name'];
        $entered_otp = $validated['otp'];
        $full_phone = $validated['code'] . $validated['phone_number'];
        $otp_record = Otp::where('phone_number', $full_phone)->latest()->first();
        if (isset($otp_record) && $entered_otp === $otp_record->otp) {
            $otp_record->delete();
            $user = User::where('Number', $phone_number)->where("is_admin", 0)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => 'default' . $phone_number . '@default.com',
                    'password' => Hash::make($full_phone),
                    'Number' => $phone_number,
                    'code' => $validated['code'],
                ]);
            }

            $token = $user->createToken('authTokenSharaaApp')->plainTextToken;

            return response()->json(['token' => $token, 'message' => 'Login Successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid OTP'], 401);
        }
    }
}
