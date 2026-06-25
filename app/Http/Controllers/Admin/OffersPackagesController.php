<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OffersPackagesController extends Controller
{
    private function getOrCreateCategory()
    {
        return Category::firstOrCreate(
            ['en_Category_Slug' => 'packages'],
            [
                'en_Category_Name' => 'Packages',
                'fr_Category_Name' => 'الباقات المتكاملة',
                'fr_Category_Slug' => 'packages',
                'Status' => 1,
                'show_on_home' => 1,
                'order' => 5
            ]
        );
    }

    public function index()
    {
        $category = $this->getOrCreateCategory();

        $packages = Product::where('Category_Id', $category->id)
            ->orderBy('id', 'desc')
            ->paginate(15);

        $title = __('Offers Packages');
        return view('admin.pages.offers_packages.index', compact('packages', 'title'));
    }

    public function create()
    {
        $title = __('Create Offers Package');
        return view('admin.pages.offers_packages.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'en_name' => 'required|string|max:255',
            'fr_name' => 'required|string|max:255',
            'en_about' => 'required|string',
            'fr_about' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0|lte:price',
            'qty' => 'required|integer|min:0',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        $category = $this->getOrCreateCategory();

        // Calculate discount percentage
        $discount = 0;
        if ($request->price > 0) {
            $discount = (($request->price - $request->discount_price) / $request->price) * 100;
        }

        // Handle slugs
        $enSlug = Str::slug($request->en_name);
        if (Product::where('en_Product_Slug', $enSlug)->exists()) {
            $enSlug .= '-' . rand(1000, 9999);
        }

        $frSlug = Str::slug($request->fr_name);
        if (Product::where('fr_Product_Slug', $frSlug)->exists()) {
            $frSlug .= '-' . rand(1000, 9999);
        }

        // Upload image
        $primaryImageName = null;
        if ($request->hasFile('primary_image')) {
            $primaryImageName = fileUpload($request->file('primary_image'), ProductImage());
        }

        Product::create([
            'en_Product_Name' => $request->en_name,
            'fr_Product_Name' => $request->fr_name,
            'en_Product_Slug' => $enSlug,
            'fr_Product_Slug' => $frSlug,
            'en_About' => $request->en_about,
            'fr_About' => $request->fr_about,
            'en_Description' => $request->en_about,
            'fr_Description' => $request->fr_about,
            'Price' => $request->price,
            'Discount_Price' => $request->discount_price,
            'Discount' => $discount,
            'Quantity' => $request->qty,
            'Category_Id' => $category->id,
            'Primary_Image' => $primaryImageName,
            'Status' => 1,
            'product_type' => 'Standard',
            'Voucher' => Str::upper(Str::random(6)),
            'type' => 1, // Physical Product
            'Brand_Id' => null,
            'en_ShippingReturn' => '',
            'fr_ShippingReturn' => '',
            'en_AdditionalInformation' => '',
            'fr_AdditionalInformation' => '',
        ]);

        return redirect()->route('admin.offers-packages.index')->with('success', __('Successfully Stored !'));
    }

    public function edit($id)
    {
        $category = $this->getOrCreateCategory();
        $package = Product::where('Category_Id', $category->id)->findOrFail($id);
        $title = __('Edit Offers Package');
        return view('admin.pages.offers_packages.edit', compact('package', 'title'));
    }

    public function update(Request $request, $id)
    {
        $category = $this->getOrCreateCategory();
        $package = Product::where('Category_Id', $category->id)->findOrFail($id);

        $request->validate([
            'en_name' => 'required|string|max:255',
            'fr_name' => 'required|string|max:255',
            'en_about' => 'required|string',
            'fr_about' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'required|numeric|min:0|lte:price',
            'qty' => 'required|integer|min:0',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
        ]);

        // Calculate discount percentage
        $discount = 0;
        if ($request->price > 0) {
            $discount = (($request->price - $request->discount_price) / $request->price) * 100;
        }

        // Slugs
        $enSlug = $package->en_Product_Slug;
        if (Str::slug($request->en_name) !== $package->en_Product_Slug) {
            $enSlug = Str::slug($request->en_name);
            if (Product::where('en_Product_Slug', $enSlug)->where('id', '!=', $package->id)->exists()) {
                $enSlug .= '-' . rand(1000, 9999);
            }
        }

        $frSlug = $package->fr_Product_Slug;
        if (Str::slug($request->fr_name) !== $package->fr_Product_Slug) {
            $frSlug = Str::slug($request->fr_name);
            if (Product::where('fr_Product_Slug', $frSlug)->where('id', '!=', $package->id)->exists()) {
                $frSlug .= '-' . rand(1000, 9999);
            }
        }

        $primaryImageName = $package->Primary_Image;
        if ($request->hasFile('primary_image')) {
            $primaryImageName = fileUpload($request->file('primary_image'), ProductImage(), $package->Primary_Image);
        }

        $package->update([
            'en_Product_Name' => $request->en_name,
            'fr_Product_Name' => $request->fr_name,
            'en_Product_Slug' => $enSlug,
            'fr_Product_Slug' => $frSlug,
            'en_About' => $request->en_about,
            'fr_About' => $request->fr_about,
            'en_Description' => $request->en_about,
            'fr_Description' => $request->fr_about,
            'Price' => $request->price,
            'Discount_Price' => $request->discount_price,
            'Discount' => $discount,
            'Quantity' => $request->qty,
            'Primary_Image' => $primaryImageName,
            'Status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.offers-packages.index')->with('success', __('Successfully Updated !'));
    }

    public function delete($id)
    {
        $category = $this->getOrCreateCategory();
        $package = Product::where('Category_Id', $category->id)->findOrFail($id);
        $package->delete();

        return redirect()->route('admin.offers-packages.index')->with('success', __('Successfully Deleted !'));
    }

    public function toggleStatus($id)
    {
        $category = $this->getOrCreateCategory();
        $package = Product::where('Category_Id', $category->id)->findOrFail($id);
        
        $package->Status = $package->Status == 1 ? 0 : 1;
        $package->save();

        return redirect()->route('admin.offers-packages.index')->with('success', __('Status updated successfully!'));
    }
}
