<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberApiController extends BaseController
{
    /**
     * Store a new newsletter subscription.
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required|email|max:255|unique:subscribers,email',
        ], [
            'email.unique' => 'You are already subscribed to our newsletter!'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $subscriber = Subscriber::create([
                'email'   => $request->email,
                'is_read' => false,
            ]);

            return $this->sendResponse($subscriber, 'Thank you for subscribing to our newsletter.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to subscribe.', ['error' => $e->getMessage()], 500);
        }
    }
}
