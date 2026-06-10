<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Admin\Color;
use App\Models\Admin\Product;
use App\Models\Admin\Size;
use App\Models\SeoSetting;
use App\Models\User;
use App\Models\WeightProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Cart;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::with('colors', 'sizes', 'comboItems')
                ->where('id', $request->product_id)
                ->first();

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Check Virtual Stock Validation FIRST
            $availableStock = $product->virtual_stock;
            $currentCartQty = 0;
            foreach (Cart::content() as $cItem) {
                if ($cItem->id == $product->id) {
                    $currentCartQty += $cItem->qty;
                }
            }

            if (($currentCartQty + $request->quantity) > $availableStock) {
                $msg = $availableStock <= 0 ? __('Out of stock') : __('Requested quantity not available. Max stock: ') . $availableStock;
                return response()->json(['error' => $msg], 422);
            }

            // Check if exact same item (with same options) already in cart
            foreach (Cart::content() as $cart) {
                if ($cart->id == $product->id && ($request->selectedSize == ($cart->options->selectedSize ?? null)) && ($request->weight_id == ($cart->options->selectedWeightId ?? null))) {
                    $qty = $cart->qty + $request->quantity;
                    Cart::update($cart->rowId, $qty);

                    $cd = Cart::content();
                    $ta = 0;
                    foreach ($cd as $item) {
                        $ta = $ta + $item->price * $item->qty;
                    }
                    $tc = Cart::count();
                    return response()->json([$tc, $ta, $cd]);
                }
            }

            $color_id = DB::table('color_product')->where('Product_Id', $request->product_id)->where('Color_Id', $request->color_id)->count();
            $size_id = DB::table('size_product')->where('Product_Id', $request->product_id)->where('Size_Id', $request->size_id)->count();
            if (isset($request->additions)) {
                $additions = DB::table('additions')->where('product_id', $request->product_id)->whereIn('id', $request->additions)->get();
            }

            $color_name = Color::where('id', $request->color_id)->first();
            $size_name = Size::where('id', $request->size_id)->first();

            $selected_size = DB::table('size_product')->where('Product_Id', $request->product_id)->where('Size_Id', $request->size_id)->first();
            $selected_weight = WeightProduct::where('product_id', $request->product_id)->where('id', $request->weight_id)->first();

            $cart = Cart::add([
                'id' => $request->product_id,
                'name' => $product->en_Product_Name,
                'qty' => $request->quantity,
                'price' => $request->price,
                'weight' => $selected_size->weight ?? 0,
                'options' =>
                    [
                        'name_ar' => $product->fr_Product_Name,
                        'additions' => $additions ?? [],
                        'size' => ($size_id > 0 && $size_name) ? $size_name->Size : null,
                        'size_ar' => ($size_id > 0 && $size_name) ? $size_name->Size_ar : null,
                        'color' => ($color_id > 0 && $color_name) ? $color_name->ColorCode : null,
                        'image' => $product->Primary_Image,
                        'weight' => $selected_weight ?? null,
                        'selectedSize' => $request->selectedSize ?? $request->size_id ?? null,
                        'selectedWeightId' => $request->weight_id ?? null,
                        'slug' => $product->en_Product_Slug,
                        'discount_price' => $request->price,
                        'item_tag' => $product->ItemTag,
                        'discount_parcent' => $product->Discount,
                        'voucher' => $product->Voucher,
                    ]
            ]);

            if ($cart) {
                $cd = Cart::content();
                $ta = 0;
                foreach ($cd as $item) {
                    $ta = $ta + $item->price * $item->qty;
                }
                $tc = Cart::count();
                return response()->json([$tc, $ta, $cd]);
            }
        }
    }




    public function cartContent()
    {
        return redirect()->route('front.cart');
    }
    public function cartDelete(Request $request)
    {
        // return response()->json($request->all());
        Session::forget('CouponAmount');
        Session::forget('couponCode');

        $id = $request->id;
        if ($id) {
            Cart::remove($id);
        }
        $cd = Cart::content();
        $ta = 0;
        foreach ($cd as $item) {
            $ta = $ta + $item->price * $item->qty;
        }
        $tc = Cart::count();
        return response()->json([
            $tc,
            $ta,
            $cd,
            'total_amount_formatted' => currencyConverter($ta)
        ]);
    }

    public function cartDecrease(Request $request)
    {
        $id = $request->id;
        $cd = Cart::content();
        $ta = 0;
        foreach (Cart::content() as $cart) {
            if ($cart->rowId == $id) {
                $qty = $request->quantity == 1 ? 1 : $cart->qty - 1;
                $singleValue = Cart::update($cart->rowId, $qty);
                $st = $singleValue->price * $singleValue->qty;

                foreach ($cd as $item) {
                    $ta = $ta + $item->price * $item->qty;
                }
                $tc = Cart::count();

                return response()->json([
                    $tc,
                    $ta,
                    $cd,
                    $st,
                    'total_amount_formatted' => currencyConverter($ta),
                    'subtotal_formatted' => currencyConverter($st)
                ]);
            }
        }
    }

    public function cartIncrease(Request $request)
    {
        $id = $request->id;
        $cd = Cart::content();
        $ta = 0;
        foreach (Cart::content() as $cart) {
            if ($cart->rowId == $id) {
                // Check Stock Validation
                $product = Product::with('comboItems')->find($cart->id); // optimize: eager load comboItems
                $availableStock = $product->virtual_stock;

                if (($cart->qty + 1) > $availableStock) {
                    return response()->json(['error' => __('Max stock reached')], 422);
                }

                $qty = $cart->qty + 1;
                $singleValue = Cart::update($cart->rowId, $qty);
                $st = $singleValue->price * $singleValue->qty;

                foreach ($cd as $item) {
                    $ta = $ta + $item->price * $item->qty;
                }
                $tc = Cart::count();

                return response()->json([
                    $tc,
                    $ta,
                    $cd,
                    $st,
                    'total_amount_formatted' => currencyConverter($ta),
                    'subtotal_formatted' => currencyConverter($st)
                ]);
            }
        }
    }

    public function currencyPrice(Request $request)
    {
        if ($request->ajax()) {
            return currencyConverter($request->price);
        }
    }

    public function currencySymbol()
    {
        return currencySymbol()[currency()];
    }
}
