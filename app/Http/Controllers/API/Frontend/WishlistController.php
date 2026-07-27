<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends BaseController
{
    /**
     * Get the logged-in user's wishlist.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Wishlist::with(['product.category', 'product.brandData'])
                ->where('user_id', $user->id)
                ->latest();

            $limit = $request->input('limit', $request->input('per_page'));
            if ($limit !== null || $request->has('paginate')) {
                $limitInt = (int) ($limit ?? 10);
                if ($limitInt <= 0) {
                    $limitInt = 10;
                }

                $wishlist = $query->paginate($limitInt);
                $wishlist->getCollection()->transform(function ($item) {
                    $product = $item->product;
                    return [
                        'wishlist_id' => $item->id,
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'short_description' => $product->short_description,
                        'price' => (float) $product->price,
                        'old_price' => $product->old_price ? (float) $product->old_price : null,
                        'image' => $product->main_image ? asset($product->main_image) : null,
                        'is_popular' => (bool) $product->is_popular,
                        'in_stock' => (bool) $product->in_stock,
                        'quantity' => (int) $product->quantity,
                        'rating' => (float) $product->rating,
                        'category' => $product->category ? $product->category->name : null,
                        'brand' => $product->brandData ? [
                            'name' => $product->brandData->name,
                            'specialty' => $product->brandData->specialty,
                            'rating' => (float) $product->brandData->rating,
                        ] : null,
                    ];
                });

                return $this->sendResponse($wishlist, 'Wishlist fetched successfully.');
            }

            $wishlist = $query->get()->map(function ($item) {
                $product = $item->product;
                return [
                    'wishlist_id' => $item->id,
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'price' => (float) $product->price,
                    'old_price' => $product->old_price ? (float) $product->old_price : null,
                    'image' => $product->main_image ? asset($product->main_image) : null,
                    'is_popular' => (bool) $product->is_popular,
                    'in_stock' => (bool) $product->in_stock,
                    'quantity' => (int) $product->quantity,
                    'rating' => (float) $product->rating,
                    'category' => $product->category ? $product->category->name : null,
                    'brand' => $product->brandData ? [
                        'name' => $product->brandData->name,
                        'specialty' => $product->brandData->specialty,
                        'rating' => (float) $product->brandData->rating,
                    ] : null,
                ];
            });

            return $this->sendResponse($wishlist, 'Wishlist fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch wishlist.', $e->getMessage());
        }
    }

    /**
     * Add or remove a product from the wishlist (toggle).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $user = Auth::user();
            $productId = $request->product_id;

            $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($exists) {
                $exists->delete();
                return $this->sendResponse(null, 'Product removed from wishlist.');
            } else {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
                return $this->sendResponse(null, 'Product added to wishlist.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Failed to update wishlist.', $e->getMessage());
        }
    }

    /**
     * Remove a product from the wishlist by ID.
     */
    public function remove($id)
    {
        try {
            $user = Auth::user();
            $wishlist = Wishlist::where('user_id', $user->id)->where('id', $id)->first();

            if (!$wishlist) {
                return $this->sendError('Item not found in wishlist.', [], 404);
            }

            $wishlist->delete();
            return $this->sendResponse(null, 'Product removed from wishlist.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to remove from wishlist.', $e->getMessage());
        }
    }
}
