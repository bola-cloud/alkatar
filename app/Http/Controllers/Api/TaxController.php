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
        $taxAmount = 0;
        $tax = null;
        if ($validated['country'] != null) {
            $countryName = $validated['country'];
            if (is_numeric($countryName)) {
                $countryModel = \App\Models\Country::find($countryName);
                if ($countryModel) {
                    $countryName = $countryModel->name_en ?? $countryModel->name;
                }
            }
            $tax = Tax::where('country', $countryName)->where('status', ACTIVE)->first();
            if (!is_null($tax)) {
                $taxAmount = ($validated['subtotal'] * $tax->percentage) / 100;
            }
        }
        return response()->json([
            'success' => true,
            'tax_amount' => $taxAmount,
            'tax_details' =>  TaxResource::make($tax),
        ], 200);
    }

}
