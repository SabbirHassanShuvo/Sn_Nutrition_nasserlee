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
            $user = Auth::user();
            $promo = PromoCode::where('code', $request->code)->first();

            if (!$promo || !$promo->isValid($user->id)) {
                return $this->sendError('Invalid or expired promo code, or you have reached the usage limit.', [], 422);
            }

            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return $this->sendError('Your cart is empty.', [], 400);
            }

            $message = $promo->getApplicabilityMessage($cartItems);

            if ($message) {
                return $this->sendError($message, [], 422);
            }
            
            $discountAmount = $promo->calculateDiscount($cartItems);

            $user->update(['applied_promo_code' => $promo->code]);

            $subtotal = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });
            
            $delivery = 50; // Fixed delivery for now
            $total = ($subtotal - $discountAmount) + $delivery;

            return $this->sendResponse([
                'items' => $cartItems->map(function ($item) {
                    $product = $item->product;
                    return [
                        'cart_id' => $item->id,
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'quantity' => (int) $item->quantity,
                        'total_price' => (float) ($product->price * $item->quantity),
                        'image' => $product->main_image ? asset($product->main_image) : null,
                    ];
                }),
                'summary' => [
                    'subtotal' => (float) $subtotal,
                    'delivery' => (float) $delivery,
                    'discount' => (float) $discountAmount,
                    'total' => (float) $total,
                    'promo_code' => $promo->code,
                    'discount_percent' => (float) $promo->discount_percent,
                ]
            ], 'Promo code applied successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to apply promo code.', $e->getMessage());
        }
    }
}
