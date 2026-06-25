<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCardPackage;
use Illuminate\Http\Request;

class GiftCardPackageController extends Controller
{
    public function index()
    {
        $data['title'] = __('Gift Card Packages');
        $data['packages'] = GiftCardPackage::orderBy('price', 'desc')->get();
        return view('admin.pages.gift_cards.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Create Gift Card Package');
        return view('admin.pages.gift_cards.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:gift_card_packages,key',
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        GiftCardPackage::create($request->only([
            'key',
            'name_ar',
            'name_en',
            'description_ar',
            'description_en',
            'price',
            'status',
        ]));

        return redirect()->route('admin.gift_card_packages.index')->with('success', __('Successfully Created'));
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Gift Card Package');
        $data['package'] = GiftCardPackage::findOrFail($id);
        return view('admin.pages.gift_cards.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $package = GiftCardPackage::findOrFail($id);

        $request->validate([
            'key' => 'required|string|unique:gift_card_packages,key,' . $id,
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        $package->update($request->only([
            'key',
            'name_ar',
            'name_en',
            'description_ar',
            'description_en',
            'price',
            'status',
        ]));

        return redirect()->route('admin.gift_card_packages.index')->with('success', __('Successfully Updated'));
    }

    public function destroy($id)
    {
        $package = GiftCardPackage::findOrFail($id);
        $package->delete();

        return redirect()->route('admin.gift_card_packages.index')->with('success', __('Successfully Deleted'));
    }
}
