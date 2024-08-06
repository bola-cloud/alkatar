<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index(Country $country)
    {
        $country->load('states');
        return StateResource::collection($country->states);
    }

    public function show(Country $country, State $state)
    {
        return StateResource::make($state);
    }
}
