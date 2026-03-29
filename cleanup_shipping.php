<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$deleted_cities = DB::table('delivery_charges')
    ->whereNotNull('city_id')
    ->whereNotExists(function($q){ 
        $q->select(DB::raw(1))
          ->from('cities')
          ->whereRaw('cities.id = delivery_charges.city_id'); 
    })->delete();

$deleted_states = DB::table('delivery_charges')
    ->whereNotNull('state_id')
    ->whereNotExists(function($q){ 
        $q->select(DB::raw(1))
          ->from('states')
          ->whereRaw('states.id = delivery_charges.state_id'); 
    })->delete();

echo "Deleted $deleted_cities orphaned city charges and $deleted_states orphaned state charges.\n";
