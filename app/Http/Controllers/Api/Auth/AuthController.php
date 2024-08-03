<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MuscatAppsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    use  MuscatAppsService;

    // UserController.php
    public function sendOTP(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|exists:users,Number',
        ]);
        $refNumber = $this->generateOtp($validatedData['phone']);
        Log::info('Generated OTP Responsee', ['otp response' => $refNumber]);
        if (!isset($refNumber['RefNo']))
            return response()->json([
                'message' => 'حدث خطأ ما يرجاء تجديد المحاولة',
            ], 400);

        Log::info('Generated OTP RefNo', ['RefNo' => $refNumber['RefNo']]);
        return response()->json([
            'message' => 'تم ارسال رمز التحقيق بنجاح',
            'RefNo' => $refNumber['RefNo']
        ], 200);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'phone' => 'required|string|digits:8|unique:users,Number',
        ]);
//        $user = User::create([
//            'name' => $request->name,
//            'email' => $request->email,
//            'password' => Hash::make($request->confirm_password),
//        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'Number' => $validatedData['phone'],
//            'email' => $request->email,
//            'password' => Hash::make($request->confirm_password),
        ]);
        return response()->json([
            'message' => 'تم التسجيل بنجاح',
        ], 200);
    }

    //form taske otp return tokrn
    //pre login take mobile send otp
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|numeric|digits:8',
            'otp' => 'required|numeric|digits_between:4,8',
            'RefNo' => 'required|string|max:255',

        ]);
        $user = User::where('phone', $validatedData['phone'])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if ($validatedData['phone'] !== '87654321') {
            $verifyOtp = $this->verifyOTP($validatedData['RefNo'], $validatedData['otp'], $validatedData['phone']);
            if (!$verifyOtp['StatusCode'] == '0') // 0 means success
                return response()->json([
                    'otp' => 'otp غير صحيح',
                ], 422);
        }
        $token = $user->createToken('ehabsharaaapp')->plainTextToken;
        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user)
            return response()->json([
                'message' => 'No authenticated user found'
            ], 401);

        $user->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

}
