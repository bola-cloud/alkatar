<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait MuscatAppsService
{
    protected function sendSms($mobile, $message, $sender = 'ZakatAlmawl',$source = 'ZakatAlmawl')
    {
        $mobile = '00968' . $mobile;
        $url = config('muscatapps.sms_api_url') ."/user/smspush.aspx?username=ZakatAlmawl&password=AbdulNaser24&phoneno=" . $mobile . "&message=" . $message . "&sender=".$sender."&source=".$source;
        $response = Http::get($url);
        Log::info('SMS sent', [
            'mobile' => $mobile,
            'message' => $message,
            'response' => $response->body()
        ]);
    }
    protected function generateOtp($phoneNumber)
    {
        $phoneNumber = '00968' . $phoneNumber;

        $url = config('muscatapps.sms_api_url') ."/api/GenOTP";
        $username = 'ZakatAlmawl';
        $password = 'AbdulNaser24';
        $companyName = 'ZakatAlmawl';
        $msgTemplate = "{OTP} is your verification code for {$companyName}";
        $payload = [
            "Phoneno" => $phoneNumber,
            "Username" => $username,
            "Password" => $password,
            "MsgTemplate" => $msgTemplate
        ];
        $response = Http::post($url, $payload);
        Log::info('OTP SMS Response', ['response' => $response->json()]);
        if ($response->successful()) {
            return $response->json();
        } else {
            Log::error('Failed to send OTP SMS', ['response' => $response->json()]);
            return null;
        }
    }
    protected function verifyOTP($refNumber, $otp , $phoneNumber )
    {
        $phoneNumber = '00968' . $phoneNumber;

        $url = config('muscatapps.sms_api_url') . "/api/VerifyOTP";
        $username = 'ZakatAlmawl';
        $password = 'AbdulNaser24';
        $payload = [
            "Phoneno" => $phoneNumber,
            "Username" => $username,
            "Password" => $password,
            "RefNo" => $refNumber,
            "OTP" => $otp,
        ];
        $response = Http::post($url, $payload);
        return  $response->json();

    }

}
