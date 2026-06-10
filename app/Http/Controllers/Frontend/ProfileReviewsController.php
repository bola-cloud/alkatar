<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewStoreRequest;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileReviewsController extends Controller
{
    /**
     * Retrieve reviews written by the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => __('Please login first')], 401);
        }

        $reviews = ProductReview::where('user_id', $user->id)
            ->with('product')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'reviews' => $reviews
        ]);
    }

    /**
     * Store a product review (or update it if already exists).
     */
    public function store(ReviewStoreRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', __('Please login first'));
        }

        $prev_review = ProductReview::where('product_id', $request->product_id)
            ->where('user_id', $user->id)
            ->first();

        if (!empty($prev_review)) {
            $updated = $prev_review->update([
                'feedback' => $request->feedback,
                'rating' => $request->rating,
            ]);
            if ($updated) {
                return redirect()->back()->with('success', __('Your review is successfully updated!'));
            }
        } else {
            $created = ProductReview::create([
                'feedback' => $request->feedback,
                'rating' => $request->rating,
                'product_id' => $request->product_id,
                'user_id' => $user->id,
                'is_visible' => true, // default to visible or admin approval
            ]);
            if ($created) {
                return redirect()->back()->with('success', __('Review submitted successfully!'));
            }
        }

        return redirect()->back()->with('error', __('Something went wrong!'));
    }
}
