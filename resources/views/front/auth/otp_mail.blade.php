<!DOCTYPE html>
<html>
<head>
    <style>
        .container {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #9fc23a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            max-height: 60px;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
        }
        .otp-box {
            background-color: #f9f9f9;
            border: 1px dashed #9fc23a;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 20px 0;
            color: #002603;
        }
        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php $allsettings = allsetting(); @endphp
            @if(isset($allsettings['main_logo']))
                <img src="{{ asset(IMG_LOGO_PATH . $allsettings['main_logo']) }}" alt="{{ config('app.name') }}">
            @else
                <h2>{{ config('app.name') }}</h2>
            @endif
        </div>
        <div class="content">
            <p>{{ __('Hello') }} {{ $user->name }},</p>
            <p>{{ __('Thank you for registering. To complete your account setup, please use the following one-time password (OTP) to verify your email address:') }}</p>
            
            <div class="otp-box">
                {{ $otp }}
            </div>
            
            <p>{{ __('This code will expire shortly. If you did not request this code, please ignore this email.') }}</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</body>
</html>
