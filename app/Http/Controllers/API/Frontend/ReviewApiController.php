<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Review;
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
}
