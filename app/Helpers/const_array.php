<?php

use App\Models\Currency;

function currency_array($currency = null)
{
    if ($currency == null) {
        return Currency::get();
    } else {
        return Currency::where('currency', '!=', $currency)->get();
    }
}

function country($input = null)
{
    $output = array(
        "AD" => ["en" => "Ad Dakhiliyah", "fr" => "الداخلية"],
        "BA" => ["en" => "Al Batinah North", "fr" => "شمال الباطنة"],
        "BS" => ["en" => "Al Batinah South", "fr" => "جنوب الباطنة"],
        "BJ" => ["en" => "Al Buraimi", "fr" => "البريمي"],
        "WU" => ["en" => "Al Wusta", "fr" => "الوسطى"],
        "DA" => ["en" => "Dhahirah", "fr" => "الظاهرة"],
        "MU" => ["en" => "Musandam", "fr" => "مسندم"],
        "SH" => ["en" => "Ash Sharqiyah North", "fr" => "شمال الشرقية"],
        "SS" => ["en" => "Ash Sharqiyah South", "fr" => "جنوب الشرقية"],
        "ZA" => ["en" => "Zahirah", "fr" => "الظاهرة"],
    );

    $lang = app()->getLocale();


    if (is_null($input)) {
        return array_map(function ($item) use ($lang) {
            return $item[$lang] ?? $item['en'];
        }, $output);
    } else {
        return $output[$input][$lang] ?? $output[$input]['en'];
    }
}
