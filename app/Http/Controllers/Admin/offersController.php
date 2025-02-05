<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Admin\Blog;
use App\Models\Admin\Category;
use App\Models\Admin\Coupon;
use App\Models\Admin\Order;
use App\Models\Offer;
use App\Models\Setting;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Admin\Product;
use Yajra\DataTables\Facades\DataTables;

class offersController extends Controller
{
    public function offers(Request $request)
    {
        if ($request->ajax()) {
            $data = Offer::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn . '<a href="' . route('admin.offers.edit', $data->id) . '" class="btn-action"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn . '<a href="' . route('admin.offers.delete', $data->id) . '" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>';

                    if ($data->Status == 1) {
                        $btn = $btn . '<a href="' . route('admin.offers.inactive', $data->id) . '" class="btn-action"><i class="fas fa-toggle-on"></i></a>';
                    } else {
                        $btn = $btn . '<a href="' . route('admin.offers.active', $data->id) . '" class="btn-action"><i class="fas fa-toggle-off"></i></a>';
                    }
                    $btn = $btn . '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['title'] = __('Offers');
        $data['free_shipping'] = Setting::where('slug', 'free_shipping')->get()[0]['value'];

        return view('admin.pages.offers.index', $data);
    }
    public function offersCreate()
    {
        $data['title'] = __('offers Create');
        $data['products'] = Product::where('status', 1)->get();
        $data['categorys'] = Category::where('Status', operator: 1)->get();
        $data['sub_categorys'] = Subcategory::where('status', 1)->get();
        return view('admin.pages.offers.create', $data);
    }
    public function offersStore(Request $request)
    {

        $offer = new Offer();
        $offer->type = $request->offer_type;
        $offer->applies_to = $request->applies_to;
        if ($request->applies_to == 'product') {
            $offer->product_id = $request->product_id;
            $product = Product::find($request->product_id);
            if ($request->is_percentage) {
                $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                $product->Discount = $request->discount_value;
            } else {
                $product->Discount_Price = $product->Price - $request->discount_value;
                $product->Discount = ($request->discount_value / $product->Price) * 100;
            }
            $product->save();
        } elseif ($request->applies_to == 'category') {
            $offer->category_id = $request->category_id;
            $products = Product::where('Category_Id', $request->category_id)->get();
            foreach ($products as $product) {
                if ($request->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                    $product->Discount = $request->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $request->discount_value;
                    $product->Discount = ($request->discount_value / $product->Price) * 100;
                }
                $product->save();
            }
        } elseif ($request->applies_to == 'sub_category') {
            $offer->sub_category_id = $request->sub_category_id;
            $products = Product::where('subcategory_id', $request->sub_category_id)->get();
            foreach ($products as $product) {
                if ($request->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                    $product->Discount = $request->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $request->discount_value;
                    $product->Discount = ($request->discount_value / $product->Price) * 100;
                }
                $product->save();
            }
        }

        $offer->discount_value = $request->discount_value;
        $offer->is_percentage = $request->is_percentage;
        $offer->name = $request->name;
        $offer->end_date = $request->end_date;
        $offer->start_date = $request->start_date;

        $offer->required_product_ids = $request->required_product_ids;
        $offer->gift_product_ids = $request->gift_product_ids;

        $offer->minimum_total = $request->minimum_total;

        $offer->save();
        if ($offer) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Stored !'));
        }
        return redirect()->back()->with('error', __('Does not insert  !'));
    }
    public function offersDelete($id)
    {
        $delete = Offer::find($id);
        if ($delete->applies_to == 'product') {
            $product = Product::find($delete->product_id);
            $product->Discount_Price = $product->Price;
            $product->Discount = 0;
            $product->save();
        } elseif ($delete->applies_to == 'category') {
            $products = Product::where('Category_Id', $delete->category_id)->get();
            foreach ($products as $product) {
                $product->Discount_Price = $product->Price;
                $product->Discount = 0;
                $product->save();
            }
        } elseif ($delete->applies_to == 'sub_category') {
            $products = Product::where('subcategory_id', $delete->sub_category_id)->get();
            foreach ($products as $product) {
                $product->Discount_Price = $product->Price;
                $product->Discount = 0;
                $product->save();
            }
        }
        $delete = $delete->delete();
        if (!empty($delete)) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Deleted !'));
        }
        return redirect()->route('admin.offers')->with('error', __('Does Not Delete!'));
    }

    public function offersEdit($id)
    {
        $data['title'] = __('offers Edit');
        $data['edit'] = Offer::where('id', $id)->first();
        $data['products'] = Product::where('status', 1)->get();
        $data['categorys'] = Category::where('Status', operator: 1)->get();
        $data['sub_categorys'] = Subcategory::where('status', 1)->get();
        return view('admin.pages.offers.edit', $data);
    }

    public function offersUpdate(Request $request)
    {
        $id = $request->id;
        $Offer = Offer::whereId($id)->first();
        if ($request->applies_to == 'product' && !is_null($request->product_id)) {
            if ($request->product_id != $Offer->product_id) {
                $product = Product::find($Offer->product_id);
                $product->Discount_Price = $product->Price;
                $product->Discount = 0;
                $product->save();
            }
            $product = Product::find($request->product_id);
            if ($request->is_percentage) {
                $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                $product->Discount = $request->discount_value;
            } else {
                $product->Discount_Price = $product->Price - $request->discount_value;
                $product->Discount = ($request->discount_value / $product->Price) * 100;
            }
            $product->save();
        } elseif ($request->applies_to == 'category' && !is_null($request->category_id)) {
            if ($request->category_id != $Offer->category_id) {
                $products = Product::where('Category_Id', $Offer->category_id)->get();
                foreach ($products as $product) {
                    $product->Discount_Price = $product->Price;
                    $product->Discount = 0;
                    $product->save();
                }
            }

            $products = Product::where('Category_Id', $request->category_id)->get();
            foreach ($products as $product) {
                if ($request->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                    $product->Discount = $request->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $request->discount_value;
                    $product->Discount = ($request->discount_value / $product->Price) * 100;
                }
                $product->save();
            }
        } elseif ($request->applies_to == 'sub_category' && !is_null($request->sub_category_id)) {
            if ($request->sub_category_id != $Offer->sub_category_id) {
                $products = Product::where('subcategory_id', $Offer->sub_category_id)->get();
                foreach ($products as $product) {
                    $product->Discount_Price = $product->Price;
                    $product->Discount = 0;
                    $product->save();
                }
            }
            $products = Product::where('subcategory_id', $request->sub_category_id)->get();
            foreach ($products as $product) {
                if ($request->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $request->discount_value / 100);
                    $product->Discount = $request->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $request->discount_value;
                    $product->Discount = ($request->discount_value / $product->Price) * 100;
                }
                $product->save();
            }
        }

        $update = $Offer->update([
            'name' => is_null($request->name) ? $Offer->name : $request->name,
            'start_date' => is_null($request->start_date) ? $Offer->start_date : $request->start_date,
            'end_date' => is_null($request->end_date) ? $Offer->end_date : $request->end_date,
            'type' => is_null($request->offer_type) ? $Offer->type : $request->offer_type,
            'applies_to' => is_null($request->applies_to) ? $Offer->applies_to : $request->applies_to,
            'product_id' => is_null($request->product_id) ? $Offer->product_id : $request->product_id,
            'category_id' => is_null($request->category_id) ? $Offer->category_id : $request->category_id,
            'sub_category_id' => is_null($request->sub_category_id) ? $Offer->sub_category_id : $request->sub_category_id,
            'required_product_ids' => is_null($request->required_product_ids) ? $Offer->required_product_ids : $request->required_product_ids,
            'gift_product_ids' => is_null($request->gift_product_ids) ? $Offer->gift_product_ids : $request->gift_product_ids,
            'discount_value' => is_null($request->discount_value) ? $Offer->discount_value : $request->discount_value,
            'is_percentage' => $request->offer_type == 'percentage_discount' ? 1 : 0,
            'minimum_total' => is_null($request->minimum_total) ? $Offer->minimum_total : $request->minimum_total,
        ]);
        if ($update) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Updated !'));
        }
        return redirect()->back()->with('error', __('Does not insert  !'));
    }


    public function freeShippingActive()
    {
        $active = Setting::where('slug', 'free_shipping')->update(['value' => 1]);
        if ($active) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Active !'));
        }
        return redirect()->route('admin.offers')->with('success', __('Does not Updated!'));
    }

    public function freeShippingInActive()
    {
        $active = Setting::where('slug', 'free_shipping')->update(['value' => 0]);
        if ($active) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Active !'));
        }
        return redirect()->route('admin.offers')->with('success', __('Does not Updated!'));
    }

    public function offersInactive($id)
    {
        $inactive = Offer::find($id);
        $inactive->update(['Status' => 0]);
        if ($inactive->applies_to == 'product') {
            $product = Product::find($inactive->product_id);
            $product->Discount_Price = $product->Price;
            $product->Discount = 0;
            $product->save();
        } elseif ($inactive->applies_to == 'sub_category') {
            $products = Product::where('subcategory_id', $inactive->sub_category_id)->get();
            foreach ($products as $product) {
                $product->Discount_Price = $product->Price;
                $product->Discount = 0;
                $product->save();
            }

        } elseif ($inactive->applies_to == 'category') {
            $products = Product::where('Category_Id', $inactive->category_id)->get();
            foreach ($products as $product) {
                $product->Discount_Price = $product->Price;
                $product->Discount = 0;
                $product->save();
            }
        }
        if ($inactive) {
            return redirect()->route('admin.offers')->with('success', __('Successfully in active !'));
        }
        return redirect()->route('admin.offers')->with('success', __('Does not Updated !'));
    }

    public function offersActive($id)
    {
        $inactive = Offer::find($id);
        $inactive->update(['Status' => 1]);
        if ($inactive->applies_to == 'product') {
            $product = Product::find($inactive->product_id);
            if ($inactive->is_percentage) {
                $product->Discount_Price = $product->Price - ($product->Price * $inactive->discount_value / 100);
                $product->Discount = $inactive->discount_value;
            } else {
                $product->Discount_Price = $product->Price - $inactive->discount_value;
                $product->Discount = ($inactive->discount_value / $product->Price) * 100;
            }
            $product->save();
        } elseif ($inactive->applies_to == 'sub_category') {
            $products = Product::where('subcategory_id', $inactive->sub_category_id)->get();
            foreach ($products as $product) {
                if ($inactive->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $inactive->discount_value / 100);
                    $product->Discount = $inactive->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $inactive->discount_value;
                    $product->Discount = ($inactive->discount_value / $product->Price) * 100;
                }
                $product->save();
            }

        } elseif ($inactive->applies_to == 'category') {
            $products = Product::where('Category_Id', $inactive->category_id)->get();
            foreach ($products as $product) {
                if ($inactive->is_percentage) {
                    $product->Discount_Price = $product->Price - ($product->Price * $inactive->discount_value / 100);
                    $product->Discount = $inactive->discount_value;
                } else {
                    $product->Discount_Price = $product->Price - $inactive->discount_value;
                    $product->Discount = ($inactive->discount_value / $product->Price) * 100;
                }
                $product->save();
            }
        }
        if ($inactive) {
            return redirect()->route('admin.offers')->with('success', __('Successfully Active !'));
        }
        return redirect()->route('admin.offers')->with('success', __('Does not Updated !'));
    }
}
