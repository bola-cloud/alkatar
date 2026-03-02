<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Models\SeoSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function userSignIn()
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin == 1) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('front');
            }
        }
        $seo = SeoSetting::where('slug', 'sign-in')->first();
        $data['title'] = $seo ? $seo->title : 'Sign In';
        $data['description'] = $seo ? $seo->description : '';
        $data['keywords'] = $seo ? $seo->keywords : '';
        // Return new-design sign in view
        return view('front.auth.newdesign_signin', $data);
    }
    public function userSignInPost(Request $request)
    {
        // Unified login logic for Email or Phone
        $rules = [
            'login_id' => 'required',
            'password' => 'required'
        ];
        $request->validate($rules);

        $login_id = $request->input('login_id');
        $is_email = filter_var($login_id, FILTER_VALIDATE_EMAIL);

        if ($is_email) {
            $user = User::where('email', $login_id)->where('is_admin', 0)->first();
        } else {
            $user = User::where('Number', $login_id)->where('is_admin', 0)->first();
        }

        if ($user) {
            if ($user->status == INACTIVE) {
                return redirect()->route('front')->with('error', __('User is blocked by admin.'));
            }
            if (Hash::check($request->password, $user->password)) {
                // Determine credential key for Auth::attempt
                $credentials = $is_email ? ['email' => $login_id] : ['Number' => $login_id];
                $credentials['password'] = $request->password;

                if (Auth::attempt($credentials)) {
                    if (Auth::user()->is_admin == 0) {
                        return redirect()->route('front');
                    } else {
                        Auth::logout();
                        return redirect()->back()->with('error', __('Something went wrong!'));
                    }
                }
            }
        }

        return redirect()->back()->with('error', __('Credential Not Match'));
    }

    public function userSignUp()
    {

        // Return the new-design registration view
        $seo = SeoSetting::where('slug', 'sign-up')->first();
        $data['title'] = $seo ? $seo->title : 'Sign Up';
        $data['description'] = $seo ? $seo->description : '';
        $data['keywords'] = $seo ? $seo->keywords : '';
        return view('front.auth.newdesign_register', $data);
    }

    public function loginModal(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->status == INACTIVE) {
                    return redirect()->route('front')->with('error', __('User is blocked by admin.'));
                }
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    if (Auth::user()->is_admin == 0) {
                        return redirect()->back()->with('success', 'Login Successfully');
                    } else {
                        Auth::logout();
                        return redirect()->back()->with('error', __('Something went wrong!'));
                    }
                }
            }
        }
        return redirect()->back()->with('error', __('Wrong Credential'));
    }
    public function userSignUpPost(UserAuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'Number' => $request->phone,
            'password' => Hash::make($request->confirm_password),
        ]);

        if ($user) {
            // Create customer in SmartLife ERP
            if (config('smartlife.sync_enabled')) {
                try {
                    $smartLifeService = new \App\Services\SmartLifeErpService();
                    $customerPhone = $request->Number ?? $request->phone ?? '';

                    $customerResult = $smartLifeService->createCustomer($user->name, $customerPhone);

                    if ($customerResult && isset($customerResult['success']) && $customerResult['success'] === true) {
                        $user->smartlife_customer_id = $customerResult['id'];
                        $user->save();

                        \Illuminate\Support\Facades\Log::info('SmartLife customer created during registration', [
                            'user_id' => $user->id,
                            'smartlife_customer_id' => $customerResult['id']
                        ]);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to create SmartLife customer during registration', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Log the user in immediately after successful registration
            try {
                Auth::login($user);
            } catch (\Exception $e) {
                // If login fails for any reason, continue but don't block the flow
            }
            return redirect()->route('front')->with('success', __('Sign Up Successfully !'));
        } else {
            return redirect()->route('user.sign.up')->with('success', __('Wrong Credential !'));
        }
    }
    public function userLogout()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('front');
        }
        return redirect()->back()->with('error', __('Something went wrong!'));
    }
    public function userChangePassword(UserChangePasswordRequest $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required',
        ]);

        $user = User::find(Auth::user()->id);
        $userPassword = $user->password;

        if (!Hash::check($request->current_password, $userPassword)) {
            return redirect()->back()->with('error', __('Current Password Not Match!'));
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->back()->with('success', __('Password change successfully!'));
    }
    //forget password
    public function userForgetPasswordGet()
    {
        $seo = SeoSetting::where('slug', 'forget-password')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        return view('front.auth.newdesign_forget_password', $data);
    }
    public function userForgetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('front.auth.mail_form', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('success', 'We have e-mailed your password reset link!');
    }
    public function userShowResetPasswordForm($token)
    {
        $seo = SeoSetting::where('slug', 'reset-password')->first();
        $data['title'] = $seo->title;
        $data['description'] = $seo->description;
        $data['keywords'] = $seo->keywords;
        $data['token'] = $token;
        return view('front.auth.newdesign_reset_password', $data);
    }
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();
        if ($user) {
            return redirect()->route('login')->with('success', 'Your password has been changed!');
        }
        return redirect()->back()->with('error', 'Your password not changed!');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();

            if ($finduser) {
                if ($finduser->status == INACTIVE) {
                    return redirect()->route('front')->with('error', __('User is blocked by admin.'));
                }
                Auth::login($finduser);
                return redirect()->route('front')->with('success', __('Login Successfully!'));
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'image' => $user->avatar,
                    'google_id' => $user->id,
                    'password' => Hash::make('123456')
                ]);
                Auth::login($newUser);
                return redirect()->route('front')->with('success', __('Login Successfully!'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();

            $finduser = User::where('facebook_id', $user->id)->first();

            if ($finduser) {
                if ($finduser->status == INACTIVE) {
                    return redirect()->route('front')->with('error', __('User is blocked by admin.'));
                }
                Auth::login($finduser);
                return redirect()->route('front')->with('success', __('Login Successfully!'));
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'image' => $user->avatar,
                    'facebook_id' => $user->id,
                    'password' => Hash::make('123456')
                ]);
                Auth::login($newUser);
                return redirect()->route('front')->with('success', __('Login Successfully!'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong!'));
        }
    }

    public function otpSignInPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'country_code' => 'required',
        ]);



        // Generate a random 6-digit OTP
        $otp = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);

        // Store OTP in session for later verification
        session(['whatsapp_otp' => $otp]);

        $phone_without_plus = ltrim($request->input('full_phone'), '+');


        // Send OTP via WhatsApp
        $response = Http::asForm()->post('https://whatsapi.alsharashoping.com/api/v1/whatsapp/send_otp', [
            'phone_number' => $phone_without_plus,
            'otp' => $otp
        ]);

        if ($response->successful()) {
            return redirect()->route('user.otp.verify.get', [
                'phone_number' => $request->input('full_phone'),
                'name' => $request->input('name'),
                'country_code' => $request->input('country_code')
            ]);
        } else {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function otpVerify(Request $request)
    {
        $data['phone_number'] = $request->phone_number;
        $data['country_code'] = $request->country_code;
        $data['name'] = $request->name;

        return view('front.auth.otp_form', $data);
    }

    public function otpVerifyPost(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'otp' => 'required|digits:5',
            'name' => 'required',
        ]);

        $phone_number = $request->input('phone_number');
        $country_code = $request->input('country_code');
        $name = $request->input('name');
        $phone_without_country_code = ltrim($phone_number, '+' . $country_code);
        $entered_otp = $request->input('otp');
        $stored_otp = session('whatsapp_otp');

        // dd($entered_otp, $stored_otp);

        if ($entered_otp === $stored_otp) {
            // OTP is valid
            session()->forget('whatsapp_otp'); // Clear the OTP from session

            $user = User::where('Number', $phone_number)->where("is_admin", 0)->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('front')->with('success', 'Login Successfully');
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => 'default' . $phone_number . '@default.com',
                    'password' => Hash::make($phone_number),
                    'code' => $country_code,
                    'Number' => $phone_number,
                ]);

                if ($user) {
                    Auth::login($user);
                    return redirect()->route('front')->with('success', __('Sign Up Successfully !'));
                }
            }
        } else {
            return redirect()->back()->with('error', 'Invalid OTP');
        }
    }

    public function completeRegistration()
    {
        return view('front.auth.completeRegistration');
    }

    public function completeRegistrationPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => Hash::make($request->input('password')),
        ]);

        if ($user) {
            return redirect()->route('front')->with('success', 'Registration Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
