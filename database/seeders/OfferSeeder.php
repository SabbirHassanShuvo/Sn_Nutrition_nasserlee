<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Whey Protein Offer
        $offer1 = Offer::create([
            'title'            => 'Up to 40% off Whey Protein',
            'sub_title'        => 'Premium isolates and concentrates from top brands.',
            'badge'            => 'Mega Sale',
            'promo_code'       => 'WHEY40',
            'bg_color'         => '#0f766e', // Dark teal/green hex
            'discount_percent' => 40.00,
            'expire_date'      => now()->addMonths(1),
            'position'         => 'top_banner',
            'status'           => true,
        ]);

        // 2. Pre-workout Bundle Offer
        $offer2 = Offer::create([
            'title'            => 'Buy 2 Get 1 Free',
            'sub_title'        => 'Mix and match across selected pre-workouts. Limited time only.',
            'badge'            => 'Bundle Deal',
            'promo_code'       => 'BUNDLE3',
            'bg_color'         => '#d97706', // Warm amber/orange hex
            'discount_percent' => 33.33,
            'expire_date'      => now()->addMonths(1),
            'position'         => 'top_banner',
            'status'           => true,
        ]);

        // 3. Normal Deals Campaign
        $offer3 = Offer::create([
            'title'            => 'Deals you\'ll love',
            'sub_title'        => 'Specially curated wellness and nutrition supplements.',
            'badge'            => 'Special Deal',
            'promo_code'       => 'LOVE15',
            'bg_color'         => '#1e1b4b', // Dark indigo hex
            'discount_percent' => 15.00,
            'expire_date'      => now()->addWeeks(2),
            'position'         => 'normal_deal',
            'status'           => true,
        ]);

        // Associate with some products
        $products = Product::limit(6)->get();

        if ($products->count() >= 2) {
            // Link first two products to Whey Protein Offer
            $offer1->products()->attach([$products[0]->id, $products[1]->id]);
        }

        if ($products->count() >= 4) {
            // Link next two products to Bundle Offer
            $offer2->products()->attach([$products[2]->id, $products[3]->id]);
        }

        if ($products->count() > 0) {
            // Link all products to the normal deals campaign
            $offer3->products()->attach($products->pluck('id')->toArray());
        }
    }
}
