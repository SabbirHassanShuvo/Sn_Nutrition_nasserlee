<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Comparison;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComparisonController extends BaseController
{
    /**
     * Get comparison list for current user.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $comparisons = Comparison::with([
                'product.category', 
                'product.brandData', 
                'product.features', 
                'product.nutrition', 
                'product.ingredients'
            ])
            ->where('user_id', $user->id)
            ->get();

            $products = $comparisons->map(function ($comp) {
                $product = $comp->product;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->main_image ? asset($product->main_image) : null,
                    'price' => (float) $product->price,
                    'category' => $product->category->name ?? null,
                    'brand' => $product->brandData->name ?? null,
                    'short_description' => $product->short_description,
                    'is_vegan' => $product->is_vegan,
                    'in_stock' => $product->in_stock,
                    'nutrition' => $product->nutrition,
                    'features' => $product->features,
                    'ingredients' => $product->ingredients,
                ];
            });

            return $this->sendResponse($products, 'Comparison list fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch comparison list.', $e->getMessage());
        }
    }

    /**
     * Add product to comparison.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $user = Auth::user();
            $productId = $request->product_id;
            $newProduct = Product::find($productId);

            // Check if already in comparison
            $exists = Comparison::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();

            if ($exists) {
                return $this->sendError('Product is already in your comparison list.', [], 422);
            }

            // Check limit (Max 4)
            $existingComparisons = Comparison::with('product')->where('user_id', $user->id)->get();
            if ($existingComparisons->count() >= 4) {
                return $this->sendError('You can only compare up to 4 products at a time.', [], 422);
            }


            Comparison::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            // Fetch updated list to return in response
            $comparisons = Comparison::with([
                'product.category', 
                'product.brandData', 
                'product.features', 
                'product.nutrition', 
                'product.ingredients'
            ])
            ->where('user_id', $user->id)
            ->get();

            $products = $comparisons->map(function ($comp) {
                $product = $comp->product;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->main_image ? asset($product->main_image) : null,
                    'price' => (float) $product->price,
                    'category' => $product->category->name ?? null,
                    'brand' => $product->brandData->name ?? null,
                    'short_description' => $product->short_description,
                    'is_vegan' => $product->is_vegan,
                    'in_stock' => $product->in_stock,
                    'nutrition' => $product->nutrition,
                    'features' => $product->features,
                    'ingredients' => $product->ingredients,
                ];
            });

            return $this->sendResponse($products, 'Product added to comparison list.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to add product to comparison.', $e->getMessage());
        }
    }

    /**
     * Remove product from comparison.
     */
    public function destroy($productId)
    {
        try {
            $user = Auth::user();
            $comparison = Comparison::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if (!$comparison) {
                return $this->sendError('Product not found in comparison list.', [], 404);
            }

            $comparison->delete();

            return $this->sendResponse([], 'Product removed from comparison list.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to remove product.', $e->getMessage());
        }
    }

    /**
     * Clear all comparisons.
     */
    public function clear()
    {
        try {
            $user = Auth::user();
            Comparison::where('user_id', $user->id)->delete();

            return $this->sendResponse([], 'Comparison list cleared.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to clear comparison list.', $e->getMessage());
        }
    }
}
