<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Fetch all users with their name and phone number.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::select('id', 'name', 'Number as phone_number')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }
}

