<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        return CountryResource::collection(Country::all());
    }
    public function show(Country $country)
    {
        return CountryResource::make($country);
    }
}
