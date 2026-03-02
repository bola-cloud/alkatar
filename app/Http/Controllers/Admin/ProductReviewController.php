<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with('product', 'user')->orderByDesc('created_at')->paginate(25);
        return view('admin.pages.product.reviews', compact('reviews'));
    }

    /** Toggle visibility (approve / hide) */
    public function toggle($id)
    {
        $r = ProductReview::findOrFail($id);
        $r->is_visible = !$r->is_visible;
        $r->save();
        return redirect()->back()->with('success', __('Review visibility updated.'));
    }

    public function destroy($id)
    {
        $r = ProductReview::findOrFail($id);
        $r->delete();
        return redirect()->back()->with('success', __('Review deleted.'));
    }
}
