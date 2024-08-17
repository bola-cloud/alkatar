<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaxResource;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'subtotal' => 'required|numeric|min:0',
            'country' => 'nullable|string|max:255',
        ]);
        $tax = tax_amount($validated['subtotal'], $validated['country'] ?? null);
        return response()->json([
            'success' => true,
            'tax' => $tax,
            'tax_details' =>  TaxResource::make($tax),
        ], 200);
    }

}
