<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileSettingsController extends Controller
{
    /**
     * Update the user profile settings.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'number' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.required' => __('Email is required'),
            'number.required' => __('Phone number is required'),
            'password.confirmed' => __('Password confirmation does not match'),
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'Number' => $request->number,
        ];

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $image = fileUpload($request->file('image'), AdminProfilePicture());
            $updateData['image'] = $image;
        }

        // Handle Password Change
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Handle notification preference updates if present
        if ($request->has('offer_types')) {
            $updateData['offer_types'] = $request->input('offer_types');
        } else {
            $updateData['offer_types'] = [];
        }

        $updated = User::where('id', $user->id)->update($updateData);

        if ($updated) {
            return redirect()->back()->with('success', __('Successfully Updated!'));
        }

        return redirect()->back()->with('error', __('Something Went Wrong!'));
    }
}
