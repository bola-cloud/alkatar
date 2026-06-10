<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    // ==========================================
    // GOVERNORATES (STATES)
    // ==========================================

    public function stateList(Request $request)
    {
        if ($request->ajax()) {
            $data = State::with('country')->select('states.*');
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<div class="action__buttons" style="display: flex; gap: 8px;">';
                    $btn .= '<button data-bs-toggle="modal" data-bs-target="#editStateModal' . $row->id . '" class="btn-action" title="' . __('Edit') . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $btn .= '<a href="' . route('admin.location.state.destroy', $row->id) . '" class="btn-action delete-item" title="' . __('Delete') . '"><i class="fas fa-trash-can text-danger"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['title'] = __('Governorates');
        $data['states'] = State::with('country')->get();
        $data['countries'] = Country::all();
        return view('admin.pages.location.state.index', $data);
    }

    public function stateStore(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
        ]);

        State::create([
            'country_id' => $request->country_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        return redirect()->back()->with('success', __('Governorate added successfully!'));
    }

    public function stateUpdate(Request $request, $id)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        return redirect()->back()->with('success', __('Governorate updated successfully!'));
    }

    public function stateDestroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();
        return redirect()->back()->with('success', __('Governorate deleted successfully!'));
    }

    // ==========================================
    // WILAYATS (CITIES)
    // ==========================================

    public function cityList(Request $request)
    {
        $states = State::all();
        $selected_state = $request->state_id ?? ($states->first()->id ?? null);

        if ($request->ajax()) {
            $data = City::with('state')->select('cities.*');
            if ($request->state_id) {
                $data->where('state_id', $request->state_id);
            }
            return DataTables::of($data)
                ->addColumn('state_name', function ($row) {
                    return $row->state->name_en ?? '';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="action__buttons" style="display: flex; gap: 8px;">';
                    $btn .= '<button data-bs-toggle="modal" data-bs-target="#editCityModal' . $row->id . '" class="btn-action" title="' . __('Edit') . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $btn .= '<a href="' . route('admin.location.city.destroy', $row->id) . '" class="btn-action delete-item" title="' . __('Delete') . '"><i class="fas fa-trash-can text-danger"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['title'] = __('Wilayats');
        $data['states'] = $states;
        $data['cities'] = City::with('state')->get();
        $data['selected_state'] = $selected_state;
        return view('admin.pages.location.city.index', $data);
    }

    public function cityStore(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
        ]);

        City::create([
            'state_id' => $request->state_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        return redirect()->back()->with('success', __('Wilayat added successfully!'));
    }

    public function cityUpdate(Request $request, $id)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
        ]);

        $city = City::findOrFail($id);
        $city->update([
            'state_id' => $request->state_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        return redirect()->back()->with('success', __('Wilayat updated successfully!'));
    }

    public function cityDestroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return redirect()->back()->with('success', __('Wilayat deleted successfully!'));
    }

    // ==========================================
    // AREAS
    // ==========================================

    public function areaList(Request $request)
    {
        $cities = City::with('state')->get();
        $selected_city = $request->city_id ?? ($cities->first()->id ?? null);

        if ($request->ajax()) {
            $data = Area::with(['city.state', 'deliveryCharge'])->select('areas.*');
            if ($request->city_id) {
                $data->where('city_id', $request->city_id);
            }
            return DataTables::of($data)
                ->addColumn('city_name', function ($row) {
                    return $row->city->name_en ?? '';
                })
                ->addColumn('state_name', function ($row) {
                    return $row->city->state->name_en ?? '';
                })
                ->addColumn('charge', function ($row) {
                    return $row->deliveryCharge->charge ?? 0;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="action__buttons" style="display: flex; gap: 8px;">';
                    $btn .= '<button data-bs-toggle="modal" data-bs-target="#editAreaModal' . $row->id . '" class="btn-action" title="' . __('Edit') . '"><i class="fa-solid fa-pen-to-square"></i></button>';
                    $btn .= '<a href="' . route('admin.location.area.destroy', $row->id) . '" class="btn-action delete-item" title="' . __('Delete') . '"><i class="fas fa-trash-can text-danger"></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['title'] = __('Areas');
        $data['cities'] = $cities;
        $data['areas'] = Area::with(['city.state', 'deliveryCharge'])->get();
        $data['selected_city'] = $selected_city;
        return view('admin.pages.location.area.index', $data);
    }

    public function areaStore(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
            'charge' => 'required|numeric|min:0',
        ]);

        $area = Area::create([
            'city_id' => $request->city_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        // Create Delivery Charge record
        DeliveryCharge::create([
            'area_id' => $area->id,
            'city_id' => $area->city_id,
            'state_id' => $area->city->state_id ?? null,
            'country' => 'Oman',
            'charge' => $request->charge,
            'status' => ACTIVE,
        ]);

        return redirect()->back()->with('success', __('Area added successfully!'));
    }

    public function areaUpdate(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name_en' => 'required|string|max:255',
            'name_fr' => 'required|string|max:255', // Arabic name
            'charge' => 'required|numeric|min:0',
        ]);

        $area = Area::findOrFail($id);
        $area->update([
            'city_id' => $request->city_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_fr,
        ]);

        // Update or Create Delivery Charge
        DeliveryCharge::updateOrCreate(
            [
                'area_id' => $area->id,
            ],
            [
                'city_id' => $area->city_id,
                'state_id' => $area->city->state_id ?? null,
                'country' => 'Oman',
                'charge' => $request->charge,
                'status' => ACTIVE,
            ]
        );

        return redirect()->back()->with('success', __('Area updated successfully!'));
    }

    public function areaDestroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        DeliveryCharge::where('area_id', $id)->delete();
        return redirect()->back()->with('success', __('Area deleted successfully!'));
    }
}
