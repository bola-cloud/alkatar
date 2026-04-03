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
        $taxAmount = tax_amount($validated['subtotal'], $validated['country']);
        
        return response()->json([
            'success' => true,
            'tax_amount' => $taxAmount,
            'tax_details' => Tax::where('country', is_numeric($validated['country']) ? (\App\Models\Country::find($validated['country'])->name_en ?? '') : $validated['country'])->first() ?? (object)[]
        ], 200);
    }

}
