<?php

namespace App\Http\Controllers\Api\Frontend\Cms;

use App\Http\Controllers\Api\BaseController;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferApiController extends BaseController
{
    /**
     * Get all active offers/campaigns and their associated products.
     */
    public function index()
    {
        try {
            $offers = Offer::active()
                ->with(['products' => function ($query) {
                    $query->where('status', 'active');
                }, 'products.category', 'products.brandData'])
                ->get()
                ->map(function ($offer) {
                    return [
                        'id'               => $offer->id,
                        'title'            => $offer->title,
                        'sub_title'        => $offer->sub_title,
                        'badge'            => $offer->badge,
                        'promo_code'       => $offer->promo_code,
                        'bg_color'         => $offer->bg_color,
                        'banner_image'     => $offer->banner_image ? asset($offer->banner_image) : null,
                        'discount_percent' => (float) $offer->discount_percent,
                        'expire_date'      => $offer->expire_date ? $offer->expire_date->toIso8601String() : null,
                        'position'         => $offer->position,
                        'products'         => $offer->products->map(function ($product) {
                            return [
                                'id'                => $product->id,
                                'name'              => $product->name,
                                'slug'              => $product->slug,
                                'short_description' => $product->short_description,
                                'price'             => (float) $product->price, // returns dynamically computed discounted price
                                'old_price'         => (float) $product->old_price, // returns base price as old_price
                                'discount_percent'  => (float) $product->discount_percent, // returns campaign discount percent
                                'offer_expiry'      => $product->offer_expiry,
                                'image'             => $product->main_image ? asset($product->main_image) : null,
                                'in_stock'          => (bool) $product->in_stock,
                                'is_popular'        => (bool) $product->is_popular,
                                'category'          => $product->category ? $product->category->name : null,
                                'brand'             => $product->brandData ? $product->brandData->name : null,
                            ];
                        }),
                    ];
                });

            return $this->sendResponse($offers, 'Active offers and campaigns retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve offers.', ['error' => $e->getMessage()]);
        }
    }
}
