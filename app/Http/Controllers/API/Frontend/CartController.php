<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    /**
     * Get the current user's cart items and summary.
     */
    public function index()
    {
        try {
            return $this->getCartResponse('Cart fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch cart.', $e->getMessage());
        }
    }

    /**
     * Helper method to get the consistent cart response.
     */
    private function getCartResponse($message)
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product.category', 'product.brandData'])
            ->where('user_id', $user->id)
            ->get();

        $formattedItems = $cartItems->map(function ($item) {
            $product = $item->product;
            return [
                'cart_id' => $item->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'short_description' => $product->short_description,
                'price' => (float) $product->price,
                'image' => $product->main_image ? asset($product->main_image) : null,
                'quantity' => (int) $item->quantity,
                'total_price' => (float) ($product->price * $item->quantity),
            ];
        });

        $subtotal = $formattedItems->sum('total_price');
        $delivery = (float) (\App\Models\Setting::first()->delivery_charge ?? 50.0);
        $discount = 0.0; 
        $promoCode = null;
        $discountPercent = 0;

        if ($user->applied_promo_code) {
            $promo = \App\Models\PromoCode::where('code', $user->applied_promo_code)->first();
            if ($promo && $promo->isValid($user->id)) {
                $promoCode = $promo->code;
                $discountPercent = (float)$promo->discount_percent;

                // Calculate targeted discount
                foreach ($cartItems as $item) {
                    $product = $item->product;
                    $isApplicable = false;

                    switch ($promo->type) {
                        case 'global':
                            $isApplicable = true;
                            break;
                        case 'category':
                            $isApplicable = $product->category_id == $promo->category_id;
                            break;
                        case 'product':
                            $isApplicable = $product->id == $promo->product_id;
                            break;
                        case 'health_professional':
                            $isApplicable = true; // Global for now
                            break;
                    }

                    if ($isApplicable) {
                        $itemTotal = $product->price * $item->quantity;
                        $discount += ($itemTotal * $promo->discount_percent) / 100;
                    }
                }
            } else {
                // If not valid anymore, clear it
                $user->update(['applied_promo_code' => null]);
            }
        }
        
        $total = ($subtotal - $discount) + $delivery;

        return $this->sendResponse([
            'items' => $formattedItems,
            'summary' => [
                'subtotal' => (float) $subtotal,
                'delivery' => (float) $delivery,
                'discount' => (float) $discount,
                'total' => (float) $total,
                'promo_code' => $promoCode,
                'discount_percent' => $discountPercent,
            ]
        ], $message);
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        try {
            $user = Auth::user();
            $productId = $request->product_id;
            $quantity = $request->get('quantity', 1);

            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
                $cartItem->refresh(); // Refresh to get the updated quantity
            } else {
                $cartItem = Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            return $this->getCartResponse('Product added to cart successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to add to cart.', $e->getMessage());
        }
    }

    /**
     * Update quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'action' => 'nullable|in:increment,decrement',
            'quantity' => 'nullable|integer|min:0',
        ]);

        try {
            $user = Auth::user();
            $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

            if (!$cartItem) {
                return $this->sendError('Cart item not found.', [], 404);
            }

            if ($request->has('quantity')) {
                $quantity = (int)$request->quantity;
                if ($quantity <= 0) {
                    $cartItem->delete();
                    return $this->getCartResponse('Product removed from cart.');
                }
                $cartItem->update(['quantity' => $quantity]);
            } elseif ($request->action === 'increment') {
                $cartItem->increment('quantity');
            } elseif ($request->action === 'decrement') {
                if ($cartItem->quantity > 1) {
                    $cartItem->decrement('quantity');
                } else {
                    $cartItem->delete();
                    return $this->getCartResponse('Product removed from cart.');
                }
            }

            return $this->getCartResponse('Cart updated successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to update cart.', $e->getMessage());
        }

    }

    /**
     * Remove an item from the cart.
     */
    public function destroy($id)
    {
        try {
            $user = Auth::user();
            $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

            if (!$cartItem) {
                return $this->sendError('Cart item not found.', [], 404);
            }

            $cartItem->delete();
            return $this->getCartResponse('Product removed from cart.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to remove from cart.', $e->getMessage());
        }
    }

    /**
     * Clear the cart.
     */
    // public function clear()
    // {
    //     try {
    //         $user = Auth::user();
    //         Cart::where('user_id', $user->id)->delete();
    //         return $this->getCartResponse('Cart cleared successfully.');
    //     } catch (\Exception $e) {
    //         return $this->sendError('Failed to clear cart.', $e->getMessage());
    //     }
    // }
}
