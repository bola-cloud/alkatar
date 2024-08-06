<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Country $country, State $state)
    {
        $state->load('cities');
        return CityResource::collection($state->cities);
    }
    public function show(Country $country, State $state, City $city)
    {
        return CityResource::make($city);
    }
}
