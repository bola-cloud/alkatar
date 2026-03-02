<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DeliveryCharge;
use App\Models\State;
use App\Models\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EcommerceController extends Controller
{
    public function countryTaxList(Request $request)
    {
        if ($request->ajax()) {
            $data = Tax::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons" style="display: flex; gap: 8px;">';
                    $btn = $btn . '<a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#editModal' . $data->id . '" style="padding: 6px 10px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn . '</div>';
                    return $btn;
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == ACTIVE) {
                        return '<span class="badge badge-pill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i class="fas fa-check-circle mr-1"></i>' . __('Active') . '</span>';
                    } else {
                        return '<span class="badge badge-pill" style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i class="fas fa-times-circle mr-1"></i>' . __('Inactive') . '</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $data['title'] = __('Tax List');
        $data['taxes'] = Tax::get();
        return view('admin.pages.tax.country', $data);
    }

    public function countryTaxStore(Request $request)
    {
        $this->validate($request, [
            'country' => 'required',
            'percentage' => 'required'
        ]);

        $tax = Tax::where('country', $request->country)->first();
        if (!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'percentage' => $request->percentage,
            ]);
            if (!empty($update)) {
                return redirect()->back()->with('success', __('Country tax already exist. It Updated!'));
            }
        } else {
            $store = Tax::create([
                'country' => $request->country,
                'percentage' => $request->percentage,
            ]);
            if (!empty($store)) {
                return redirect()->back()->with('success', __('Country tax added!'));
            }
        }
        return redirect()->back()->with('error', __('Something went wrong'));
    }

    public function countryTaxUpdate(Request $request, $id)
    {
        $id = decrypt($id);
        $tax = Tax::where('id', $id)->first();
        if (!is_null($tax)) {
            $update = $tax->update([
                'country' => $request->country,
                'percentage' => $request->percentage,
                'status' => $request->status,
            ]);
            if (!empty($update)) {
                return redirect()->back()->with('success', __('Country tax Updated!'));
            }
        }
        return redirect()->back()->with('error', __('Something went wrong'));
    }

    public function countryDCList(Request $request)
    {
        if ($request->ajax()) {
            // eager load relations so DataTables closures can access related names reliably
            // Filter out rows that are for specific Areas (area_id is not null)
            $data = DeliveryCharge::with('city', 'state')->whereNull('area_id')->select('delivery_charges.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons" style="display: flex; gap: 8px;">';
                    // Show Edit button (Modal) ONLY for State level (no city_id)
                    // For Cities, we use "Manage Areas" instead
                    if (!$data->city_id) {
                        $btn .= '<a href="javascript:void(0)" class="btn-action" data-bs-toggle="modal" data-bs-target="#editModal' . $data->id . '" title="' . __('Edit') . '" style="padding: 6px 10px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-pen-to-square"></i></a>';
                    }

                    // If this is a city-level charge (has city_id), allow managing its areas
                    if (!is_null($data->city_id)) {
                        $url = route('admin.city_areas', $data->city_id);
                        $btn .= '<a href="' . $url . '" class="btn-action ms-2" title="' . __('Manage Areas') . '" style="padding: 6px 10px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><i class="fa-solid fa-list"></i></a>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })
                // provide explicit state_name and city_name fields for the client
                ->addColumn('state_name', function ($data) {
                    return langConverter(optional($data->state)->name_en, null, optional($data->state)->name_ar);
                })
                ->addColumn('city_name', function ($data) {
                    return langConverter(optional($data->city)->name_en, null, optional($data->city)->name_ar);
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == ACTIVE) {
                        return '<span class="badge badge-pill" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i class="fas fa-check-circle mr-1"></i>' . __('Active') . '</span>';
                    } else {
                        return '<span class="badge badge-pill" style="background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); color: white; padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; white-space: nowrap; border-radius: 50rem;"><i class="fas fa-times-circle mr-1"></i>' . __('Inactive') . '</span>';
                    }
                })
                ->rawColumns(['action', 'status', 'state_name', 'city_name', 'area_name'])
                ->make(true);
        }
        $data['title'] = __('Delivery Charge List');
        $data['delivery_charges'] = DeliveryCharge::with('city', 'state', 'area')->get();
        $oman = Country::where('name_en', 'Oman')->first();
        $oman_country_id = $oman ? $oman->id : null;
        $data['states'] = $oman_country_id ? State::where('country_id', $oman_country_id)->get() : collect();
        // dd($data['delivery_charges']);

        return view('admin.pages.delivery-charge.country', $data);
    }

    public function countryDCStore(Request $request)
    {
        $store = DeliveryCharge::create([
            'country' => $request->country,
            'charge' => $request->charge,
            'city_id' => $request->city_id,
            'state_id' => $request->state_id,
            'area_id' => $request->area_id,
        ]);

        if (!empty($store)) {
            return redirect()->back()->with('success', __('Delivery charge added!'));
        }

        return redirect()->back()->with('error', __('Something went wrong'));
    }

    public function countryDCUpdate(Request $request, $id)
    {
        $id = decrypt($id);
        $update = DeliveryCharge::whereId($id)->update([
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'area_id' => $request->area_id ?? null,
            'charge' => $request->charge,
            'status' => $request->status,
        ]);
        if (!empty($update)) {
            return redirect()->back()->with('success', __('Delivery Charge Updated!'));
        }
        return redirect()->back()->with('error', __('Something went wrong'));
    }

    public function cityAreas($city_id)
    {
        $city = \App\Models\City::findOrFail($city_id);
        // Get all areas for this city, and join with delivery_charges if exists
        // Or cleaner: Fetch all areas, and for each, fetch/show existing charge.

        $areas = \App\Models\Area::where('city_id', $city_id)->get();
        // Fetch existing charges keyed by area_id
        $charges = DeliveryCharge::where('city_id', $city_id)
            ->whereNotNull('area_id')
            ->pluck('charge', 'area_id');

        $data['title'] = __('Manage Areas for') . ' ' . $city->name_en;
        $data['city'] = $city;
        $data['areas'] = $areas;
        $data['charges'] = $charges;

        return view('admin.pages.delivery-charge.areas', $data);
    }

    public function updateCityAreaCharges(Request $request)
    {
        $city_id = $request->city_id;
        $charges = $request->charges; // array of area_id => charge

        foreach ($charges as $area_id => $charge) {
            // Find or Create Delivery Charge for this Area
            // Use updateOrCreate
            $area = \App\Models\Area::find($area_id);
            if ($area) {
                DeliveryCharge::updateOrCreate(
                    [
                        'area_id' => $area_id,
                        'city_id' => $city_id,
                    ],
                    [
                        'state_id' => $area->city->state_id ?? null, // Ensure state_id is correct
                        'country' => 'Oman',
                        'charge' => $charge,
                        'status' => ACTIVE,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', __('Area Charges Updated Successfully!'));
    }
}
