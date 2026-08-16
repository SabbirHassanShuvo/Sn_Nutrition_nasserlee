<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Review;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewApiController extends BaseController
{
    /**
     * Submit a new review.
     */
    public function submitReview(Request $request)
    {
        $isAuth = auth('api')->check();

        $validator = Validator::make($request->all(), [
            'user_id' => $isAuth ? 'nullable|exists:users,id' : 'required|exists:users,id',
            'quote'   => 'required|string',
            'rating'  => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $userId = $request->user_id ?? auth('api')->id();

            $review = Review::create([
                'user_id' => $userId,
                'quote'   => $request->quote,
                'rating'  => $request->rating,
            ]);

            return $this->sendResponse($review, 'Review submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to submit review.', ['error' => $e->getMessage()], 500);
        }
    }

    public function getReviews()
    {
        try {
            $reviews = Review::with(['user.partnerProfile'])->get();

            $formattedReviews = $reviews->map(function ($review) {
                return [
                    'id' => 'testimonial-' . $review->id,
                    'quote' => $review->quote,
                    'rating' => (int) $review->rating,
                    'author' => [
                        'name' => $review->user->name ?? null,
                        'role' => $review->user->partnerProfile->professional_role ?? null,
                        'avatarUrl' => $review->user->partnerProfile->avatar ?? null,
                    ]
                ];
            });

            return $this->sendResponse($formattedReviews, 'Reviews retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve reviews.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Submit a new product review and recalculate average rating.
     */
    public function submitProductReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $userId = auth('api')->id();

            $review = ProductReview::updateOrCreate(
                [
                    'user_id' => $userId,
                    'product_id' => $request->product_id,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]
            );

            // Recalculate average rating and review count
            $product = Product::find($request->product_id);
            $reviewsCount = $product->reviews()->count();
            $avgRating = $product->reviews()->avg('rating');

            $product->update([
                'rating' => round($avgRating ?? 0, 1),
                'reviews_count' => $reviewsCount,
            ]);

            return $this->sendResponse([
                'review' => $review,
                'product_rating' => (float) $product->rating,
                'product_reviews_count' => (int) $product->reviews_count,
            ], 'Product review submitted and ratings updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to submit product review.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get reviews for a specific product.
     */
    public function getProductReviews($productId)
    {
        try {
            $product = Product::find($productId);
            if (!$product) {
                return $this->sendError('Product not found.', [], 404);
            }

            $reviews = ProductReview::with('user')
                ->where('product_id', $productId)
                ->latest()
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'comment' => $review->comment,
                        'rating' => (int) $review->rating,
                        'user_name' => $review->user->name ?? 'Anonymous',
                        'created_at' => $review->created_at ? $review->created_at->toIso8601String() : null,
                    ];
                });

            return $this->sendResponse([
                'product_id' => (int) $productId,
                'rating' => (float) $product->rating,
                'reviews_count' => (int) $product->reviews_count,
                'reviews' => $reviews,
            ], 'Product reviews retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve product reviews.', ['error' => $e->getMessage()], 500);
        }
    }
}
