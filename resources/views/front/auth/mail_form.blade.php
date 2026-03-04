<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f6;
            padding-bottom: 40px;
        }

        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #1e293b;
            border-radius: 8px;
            margin-top: 40px;
        }

        .header {
            background-color: #ffffff;
            padding: 40px 0;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 40px 50px;
            line-height: 1.6;
        }

        .footer {
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }

        .button {
            background-color: #929f1a;
            color: #ffffff !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .logo {
            max-height: 50px;
            width: auto;
        }

        h1 {
            font-size: 24px;
            color: #1e293b;
            margin-top: 0;
        }

        p {
            margin: 16px 0;
        }

        .security-note {
            font-size: 14px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <img src="https://c.animaapp.com/mhnmip5wa2i9Oh/img/hi-speed--4-send---final--06-3.png"
                        alt="HiSpeed Logo" class="logo">
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>{{ __('Reset Your Password') }}</h1>
                    <p>{{ __('Hello!') }}</p>
                    <p>{{ __('We received a request to reset the password for your account at HiSpeed.') }}</p>
                    <p>{{ __('Click the button below to set a new password:') }}</p>
                    <div style="text-align: center;">
                        <a href="{{ route('reset.password.get', $token) }}"
                            class="button">{{ __('RESET PASSWORD') }}</a>
                    </div>
                    <p>{{ __('This password reset link will expire in 60 minutes.') }}</p>
                    <p>{{ __('If you did not request a password reset, no further action is required.') }}</p>
                    <div class="security-note">
                        <p>{{ __('If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:') }}
                        </p>
                        <p style="word-break: break-all; color: #929f1a;">{{ route('reset.password.get', $token) }}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}<br>
                    {{ __('Muscat, Oman') }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>