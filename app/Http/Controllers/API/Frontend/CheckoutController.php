<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\BankTransfer;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends BaseController
{
    /**
     * Get checkout details and summary.
     */
    public function getCheckoutDetails(Request $request)
    {
        try {
            $user = Auth::user();
            $cartItems = Cart::with(['product'])->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return $this->sendError('Your cart is empty.');
            }

            $items = $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product->name,
                    'short_description' => $item->product->short_description,
                    'image' => $item->product->main_image ? asset($item->product->main_image) : null,
                    'price' => (float) $item->product->price,
                    'quantity' => (int) $item->quantity,
                    'total' => (float) ($item->product->price * $item->quantity)
                ];
            });

            $subtotal = $items->sum('total');
            
            // Delivery methods
            $deliveryFee = $request->delivery_method === 'express' ? 80.0 : 0.0;
            
            $discount = 0.0;
            if ($request->promo_code) {
                $promo = \App\Models\PromoCode::where('code', $request->promo_code)->first();
                if ($promo && $promo->isValid()) {
                    $discount = $promo->calculateDiscount($cartItems);
                }
            }

            $total = ($subtotal - $discount) + $deliveryFee;

            return $this->sendResponse([
                'items' => $items,
                'summary' => [
                    'subtotal' => (float) $subtotal,
                    'delivery_fee' => (float) $deliveryFee,
                    'discount' => (float) $discount,
                    'total' => (float) $total
                ]
            ], 'Checkout details fetched.');
        } catch (Exception $e) {
            return $this->sendError('Failed to fetch checkout details.', $e->getMessage());
        }
    }

    public function submitBankTransfer(Request $request)
    {
        try {
            $user = Auth::user();
            $cartExists = Cart::where('user_id', $user->id)->exists();

            if (!$cartExists) {
                return $this->sendError('Your cart is empty. Please add products to cart first.');
            }

            $request->validate([
                'sender_full_name' => 'required|string',
                'sender_bank' => 'required|string',
                'account_last_4' => 'required|string|size:4',
                'amount_paid' => 'required|numeric',
                'transfer_reference' => 'nullable|string',
                'receipt_image' => 'required|image|max:5120', // Max 5MB
            ]);

            $imagePath = null;
            if ($request->hasFile('receipt_image')) {
                $file = $request->file('receipt_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $uploadPath = public_path('uploads/receipts');
                
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $file->move($uploadPath, $filename);
                $imagePath = 'uploads/receipts/' . $filename;
            }

            $transfer = BankTransfer::create([
                'sender_full_name' => $request->sender_full_name,
                'sender_bank' => $request->sender_bank,
                'account_last_4' => $request->account_last_4,
                'amount_paid' => $request->amount_paid,
                'transfer_reference' => $request->transfer_reference,
                'receipt_image' => $imagePath,
            ]);

            return $this->sendResponse([
                'bank_transfer_id' => $transfer->id
            ], 'Payment receipt submitted successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to submit receipt.', $e->getMessage());
        }
    }

    /**
     * Place an order.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            // Contact Information
            'phone' => 'required|string',
            'full_name' => 'required|string',
            'email' => 'required|email',
            
            // Shipping Address
            'city' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            
            // Delivery & Payment
            'delivery_method' => 'required|in:standard,express',
            'payment_method' => 'required|in:cod,bank_transfer',
            'preferred_delivery_date' => 'nullable|date',
            'promo_code' => 'nullable|string',
            'bank_transfer_id' => 'required_if:payment_method,bank_transfer|exists:bank_transfers,id',
        ]);

        try {
            $user = Auth::user();
            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

            if ($cartItems->isEmpty()) {
                return $this->sendError('Your cart is empty.');
            }

            return DB::transaction(function () use ($request, $user, $cartItems) {
                $subtotal = $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });

                $deliveryFee = $request->delivery_method === 'express' ? 80.0 : 0.0;
                
                $discount = 0.0;
                $appliedPromoCode = null;

                $promoCodeStr = $request->promo_code ?? $user->applied_promo_code;
                
                if ($promoCodeStr) {
                    $promo = PromoCode::where('code', $promoCodeStr)->first();
                    if ($promo && $promo->isValid($user->id)) {
                        $discount = $promo->calculateDiscount($cartItems);
                        $appliedPromoCode = $promo->code;
                        $promo->increment('used_count');
                    }
                }

                $total = $subtotal + $deliveryFee - $discount;

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => 'SN-' . strtoupper(Str::random(6)),
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'applied_promo_code' => $appliedPromoCode,
                    'discount' => $discount,
                    'total' => $total,
                    'status' => 'pending',
                    'bank_transfer_id' => $request->bank_transfer_id,
                    
                    'phone' => $request->phone,
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'city' => $request->city,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'country' => $request->country,
                    
                    'delivery_method' => $request->delivery_method,
                    'payment_method' => $request->payment_method,
                    'preferred_delivery_date' => $request->preferred_delivery_date,
                ]);

                // Link the transfer back to the order
                if ($request->bank_transfer_id) {
                    BankTransfer::where('id', $request->bank_transfer_id)->update(['order_id' => $order->id]);
                }

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                }

                // Clear cart after order
                Cart::where('user_id', $user->id)->delete();

                return $this->sendResponse([
                    'order_number' => $order->order_number,
                    'total' => (float) $order->total,
                    'payment_method' => $order->payment_method,
                ], 'Order placed successfully.');
            });
        } catch (\Exception $e) {
            return $this->sendError('Failed to place order.', $e->getMessage());
        }
    }
}
