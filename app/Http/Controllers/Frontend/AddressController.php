<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = $user ? $user->addresses()->orderByDesc('is_default')->get() : [];
        return response()->json(['addresses' => $addresses]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);

        $v = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'recipient_name' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean'
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        DB::transaction(function () use ($request, $user, &$address) {
            if ($request->filled('is_default') && $request->boolean('is_default')) {
                // unset other defaults
                Address::where('user_id', $user->id)->update(['is_default' => false]);
            }

            $address = Address::create(array_merge($request->only([
                'label','recipient_name','phone','address_line1','address_line2','city','state','postal_code','country','latitude','longitude'
            ]), [
                'user_id' => $user->id,
                'is_default' => $request->boolean('is_default', false),
                'address_type' => $request->input('address_type', 'both')
            ]));
        });

        return response()->json(['address' => $address], 201);
    }

    public function update(Request $request, Address $address)
    {
        $user = Auth::user();
        if (!$user || $address->user_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 401);

        $v = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'recipient_name' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean'
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        DB::transaction(function () use ($request, $user, $address) {
            if ($request->filled('is_default') && $request->boolean('is_default')) {
                Address::where('user_id', $user->id)->update(['is_default' => false]);
            }

            $address->update(array_merge($request->only([
                'label','recipient_name','phone','address_line1','address_line2','city','state','postal_code','country','latitude','longitude'
            ]), [
                'is_default' => $request->boolean('is_default', $address->is_default),
                'address_type' => $request->input('address_type', $address->address_type)
            ]));
        });

        return response()->json(['address' => $address]);
    }

    public function destroy(Request $request, Address $address)
    {
        $user = Auth::user();
        if (!$user || $address->user_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 401);
        $address->delete();
        return response()->json(['deleted' => true]);
    }

    public function setDefault(Request $request, Address $address)
    {
        $user = Auth::user();
        if (!$user || $address->user_id !== $user->id) return response()->json(['message' => 'Unauthorized'], 401);

        DB::transaction(function () use ($user, $address) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return response()->json(['address' => $address]);
    }
}
