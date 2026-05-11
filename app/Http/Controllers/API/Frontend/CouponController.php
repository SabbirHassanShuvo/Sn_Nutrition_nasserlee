<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Models\PromoCode;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;

class CouponController extends BaseController
{
    /**
     * Apply a coupon code to the current cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $promo = PromoCode::where('code', $request->code)->first();

            if (!$promo || !$promo->isValid()) {
                return $this->sendError('Invalid or expired promo code.', [], 422);
            }

            $user = Auth::user();
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return $this->sendError('Your cart is empty.', [], 400);
            }

            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->product->price * $item->quantity;
            }

            $discountAmount = ($subtotal * $promo->discount_percent) / 100;
            $delivery = 50; // Fixed delivery for now
            $total = ($subtotal - $discountAmount) + $delivery;

            return $this->sendResponse([
                'promo_code' => $promo->code,
                'discount_percent' => (float)$promo->discount_percent,
                'discount_amount' => (float)$discountAmount,
                'subtotal' => (float)$subtotal,
                'delivery' => (float)$delivery,
                'total' => (float)$total
            ], 'Promo code applied successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to apply promo code.', $e->getMessage());
        }
    }
}
