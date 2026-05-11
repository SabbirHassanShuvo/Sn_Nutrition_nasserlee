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
     * Get the current user's cart items.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $cartItems = Cart::with(['product.category', 'product.brandData'])
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($item) {
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

            $subtotal = $cartItems->sum('total_price');
            $delivery = 0.0; 
            $discount = 0.0; 
            $total = $subtotal + $delivery - $discount;

            return $this->sendResponse([
                'items' => $cartItems,
                'summary' => [
                    'subtotal' => (float) $subtotal,
                    'delivery' => (float) $delivery,
                    'discount' => (float) $discount,
                    'total' => (float) $total,
                ]
            ], 'Cart fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch cart.', $e->getMessage());
        }
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
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            return $this->sendResponse(null, 'Product added to cart successfully.');
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
            'action' => 'required|in:increment,decrement',
        ]);

        try {
            $user = Auth::user();
            $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

            if (!$cartItem) {
                return $this->sendError('Cart item not found.', [], 404);
            }

            if ($request->action === 'increment') {
                $cartItem->increment('quantity');
            } else {
                if ($cartItem->quantity > 1) {
                    $cartItem->decrement('quantity');
                } else {
                    $cartItem->delete();
                    return $this->sendResponse(null, 'Product removed from cart.');
                }
            }

            return $this->sendResponse(null, 'Cart updated successfully.');
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
            return $this->sendResponse(null, 'Product removed from cart.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to remove from cart.', $e->getMessage());
        }
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        try {
            $user = Auth::user();
            Cart::where('user_id', $user->id)->delete();
            return $this->sendResponse(null, 'Cart cleared successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to clear cart.', $e->getMessage());
        }
    }
}
