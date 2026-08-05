<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends BaseController
{
    /**
     * Helper to get wishlisted product IDs for current user.
     */
    private function getWishlistProductIds(Request $request)
    {
        $user = null;
        try {
            $user = auth('api')->user() ?: Auth::user();
        } catch (\Exception $e) {
            $user = null;
        }

        if ($user) {
            return Wishlist::where('user_id', $user->id)->pluck('product_id')->toArray();
        }

        return [];
    }

    /**
     * Get all active products for the home page.
     */
    public function getAllProducts(Request $request)
    {
        try {
            $limit = (int) $request->input('limit', $request->input('per_page', 12));
            if ($limit <= 0) {
                $limit = 12;
            }

            $wishlistProductIds = $this->getWishlistProductIds($request);

            $products = Product::with(['category', 'brandData', 'batch'])
                ->where('status', 'active')
                ->latest()
                ->paginate($limit);

            $products->getCollection()->transform(function ($product) use ($wishlistProductIds) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'price' => (float) $product->price,
                    'old_price' => $product->old_price ? (float) $product->old_price : null,
                    'image' => $product->main_image ? asset($product->main_image) : null,
                    'in_stock' => (bool) $product->in_stock,
                    'is_wishlist' => in_array($product->id, $wishlistProductIds),
                    'quantity' => (int) $product->quantity,
                    'rating' => (float) $product->rating,
                    'category' => $product->category ? $product->category->name : null,
                    'brand' => $product->brandData ? [
                        'name' => $product->brandData->name,
                        'specialty' => $product->brandData->specialty,
                        'rating' => (float) $product->brandData->rating,
                    ] : null,
                    'batch' => $product->batch ? [
                        'id' => (int) $product->batch->id,
                        'name' => $product->batch->name,
                        'color' => $product->batch->color,
                    ] : null,
                ];
            });

            return $this->sendResponse($products, 'Products fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch products.', $e->getMessage());
        }
    }

    /**
     * Filter products based on search query, category, brand, and price.
     */
    public function filterProducts(Request $request)
    {
        try {
            $wishlistProductIds = $this->getWishlistProductIds($request);

            $query = Product::with(['category', 'brandData', 'batch'])->where('status', 'active');

            // Search by name or description
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('short_description', 'like', "%$search%");
                });
            }

            // Filter by category (single or multiple)
            $catParam = $request->input('category_id', $request->input('categories'));
            if ($catParam !== null && $catParam !== '') {
                if (is_string($catParam) && str_starts_with($catParam, '[') && str_ends_with($catParam, ']')) {
                    $catParam = json_decode($catParam, true);
                }
                $categoryIds = is_array($catParam) ? $catParam : array_map('trim', explode(',', $catParam));
                $query->whereIn('category_id', $categoryIds);
            }

            // Filter by brand (single or multiple)
            $brandParam = $request->input('brand_id', $request->input('brands'));
            if ($brandParam !== null && $brandParam !== '') {
                if (is_string($brandParam) && str_starts_with($brandParam, '[') && str_ends_with($brandParam, ']')) {
                    $brandParam = json_decode($brandParam, true);
                }
                $brandIds = is_array($brandParam) ? $brandParam : array_map('trim', explode(',', $brandParam));
                $query->whereIn('brand_id', $brandIds);
            }

            // Filter by price range
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            
            // If they send price=100,500
            if ($request->filled('price') && str_contains($request->price, ',')) {
                $prices = array_map('trim', explode(',', $request->price));
                $minPrice = $prices[0] ?? $minPrice;
                $maxPrice = $prices[1] ?? $maxPrice;
            }

            if ($minPrice !== null && $minPrice !== '') {
                $query->where('price', '>=', (float) $minPrice);
            }
            if ($maxPrice !== null && $maxPrice !== '') {
                $query->where('price', '<=', (float) $maxPrice);
            }

            // Sorting
            $sort = $request->get('sort', 'latest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }

            $limit = (int) $request->input('limit', $request->input('per_page', 12));
            if ($limit <= 0) {
                $limit = 12;
            }

            $products = $query->paginate($limit);

            $products->getCollection()->transform(function ($product) use ($wishlistProductIds) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'price' => (float) $product->price,
                    'old_price' => $product->old_price ? (float) $product->old_price : null,
                    'image' => $product->main_image ? asset($product->main_image) : null,
                    'in_stock' => (bool) $product->in_stock,
                    'is_wishlist' => in_array($product->id, $wishlistProductIds),
                    'quantity' => (int) $product->quantity,
                    'rating' => (float) $product->rating,
                    'category' => $product->category ? $product->category->name : null,
                    'brand' => $product->brandData ? [
                        'name' => $product->brandData->name,
                        'specialty' => $product->brandData->specialty,
                        'rating' => (float) $product->brandData->rating,
                    ] : null,
                    'batch' => $product->batch ? [
                        'id' => (int) $product->batch->id,
                        'name' => $product->batch->name,
                        'color' => $product->batch->color,
                    ] : null,
                ];
            });

            return $this->sendResponse($products, 'Filtered products fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to filter products.', $e->getMessage());
        }
    }

    /**
     * Get available categories and brands for filtering.
     */
    public function getFilters()
    {
        try {
            $categories = Category::select('id', 'name')->where('status', 'active')->get();
            $brands = Brand::select('id', 'name', 'specialty', 'rating')->where('status', 'active')->get();

            return $this->sendResponse([
                'categories' => $categories,
                'brands' => $brands,
                'price_range' => [
                    'min' => (float) Product::min('price'),
                    'max' => (float) Product::max('price'),
                ]
            ], 'Filters fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch filters.', $e->getMessage());
        }
    }

    /**
     * Get detailed information for a single product by id.
     */
    public function getProductDetails(Request $request, $id)
    {
        try {
            $product = Product::with([
                'category', 
                'brandData', 
                'batch',
                'features', 
                'ingredients', 
                'usages', 
                'nutrition'
            ])
            ->where('status', 'active')
            ->where('id', $id)
            ->first();

            if (!$product) {
                return $this->sendError('Product not found.', [], 404);
            }

            $wishlistProductIds = $this->getWishlistProductIds($request);

            // Format gallery images
            $galleryImages = [];
            if (!empty($product->gallery_images) && is_array($product->gallery_images)) {
                foreach ($product->gallery_images as $image) {
                    $galleryImages[] = asset($image);
                }
            }

            $productDetails = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'short_description' => $product->short_description,
                'full_description' => $product->full_description,
                'price' => (float) $product->price,
                'old_price' => $product->old_price ? (float) $product->old_price : null,
                'image' => $product->main_image ? asset($product->main_image) : null,
                'gallery_images' => $galleryImages,
                'in_stock' => (bool) $product->in_stock,
                'is_wishlist' => in_array($product->id, $wishlistProductIds),
                'quantity' => (int) $product->quantity,
                'rating' => (float) $product->rating,
                'reviews_count' => (int) $product->reviews_count,
                'form' => $product->form,
                'servings' => $product->servings,
                'is_vegan' => (bool) $product->is_vegan,
                'category' => $product->category ? $product->category->name : null,
                'brand' => $product->brandData ? [
                    'name' => $product->brandData->name,
                    'specialty' => $product->brandData->specialty,
                    'rating' => (float) $product->brandData->rating,
                ] : null,
                'batch' => $product->batch ? [
                    'id' => (int) $product->batch->id,
                    'name' => $product->batch->name,
                    'color' => $product->batch->color,
                ] : null,
                'features' => $product->features->map(function ($feature) {
                    return [
                        'id' => $feature->id,
                        'title' => $feature->title,
                        'description' => $feature->description
                    ];
                }),
                'ingredients' => $product->ingredients->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'title' => $ingredient->title,
                        'description' => $ingredient->description
                    ];
                }),
                'usages' => $product->usages->map(function ($usage) {
                    return [
                        'id' => $usage->id,
                        'type' => $usage->type,
                        'content' => $usage->content
                    ];
                }),
                'nutrition' => $product->nutrition->map(function ($nutri) {
                    return [
                        'id' => $nutri->id,
                        'name' => $nutri->name,
                        'amount' => $nutri->amount
                    ];
                }),
            ];

            return $this->sendResponse($productDetails, 'Product details fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch product details.', $e->getMessage());
        }
    }
}
