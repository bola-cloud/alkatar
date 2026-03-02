<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        // Use guest middleware for admin guard when applicable
        $this->middleware('guest:admin')->except('logout');
    }
    public  function  login()
    {
        return view('admin.auth.signin');
    }
    public  function LoginDashboard(Request $request)
    {
        // Attempt to find admin in the admins table (Admin model)
        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            // Optional: if you have a status field for admins, check it here (uncomment if used)
            // if (isset($admin->status) && $admin->status == INACTIVE) {
            //     return redirect()->route('admin.login')->with('error', __('User is blocked by admin.'));
            // }

            // Verify password and attempt authentication using admin guard
            if (Hash::check($request->password, $admin->password)) {
                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    // Successful admin login
                    return redirect()->route('admin.dashboard');
                }
            }
        }

        return redirect()->route('admin.login')->with('error', __('Wrong Credential'));
    }
    public function logout()
    {
        // Logout from admin guard
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login');
        }
        return redirect()->back()->with('error', __('Something went wrong!'));
    }
}
