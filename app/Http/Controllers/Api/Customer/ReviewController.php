<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function storeReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'product_id' => ['required']
        ]);

        try {
            $review = DB::transaction(function () use ($request) {

                $review = Review::create([
                    'product_id' => $request->product_id,
                    'user_id' => Auth::user()->id,
                    'rating' => $request->rating,
                    'review' => $request->review,
                ]);
                return $review;
            });
            if ($review) {
                return responseSuccess($review, 200, 'Review added successfully');
            }
        } catch (\Exception $e) {
            return responseError($e->getMessage(), 500);
        }
    }
}