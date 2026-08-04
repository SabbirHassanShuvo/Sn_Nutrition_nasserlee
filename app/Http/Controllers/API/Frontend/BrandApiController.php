<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Api\BaseController;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandApiController extends BaseController
{
    /**
     * Get all active brands with product count.
     */
    public function index()
    {
        try {
            $brands = Brand::withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->where('status', 'active')
            ->get();

            $formattedBrands = $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'slug' => $brand->slug,
                    'specialty' => $brand->specialty,
                    'rating' => (float) $brand->rating,
                    'image' => $brand->image ? asset($brand->image) : null,
                    'products_count' => $brand->products_count,
                ];
            });

            return $this->sendResponse($formattedBrands, 'Brands fetched successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to fetch brands.', $e->getMessage());
        }
    }
}
