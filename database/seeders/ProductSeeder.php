<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $on = Brand::where('slug', 'optimum-nutrition')->first();
        $muscletech = Brand::where('slug', 'muscletech')->first();
        $dymatize = Brand::where('slug', 'dymatize')->first();
        $cellucor = Brand::where('slug', 'cellucor')->first();
        $myprotein = Brand::where('slug', 'myprotein')->first();

        $protein = Category::where('slug', 'whey-protein')->first();
        $preworkout = Category::where('slug', 'pre-workout')->first();
        $creatine = Category::where('slug', 'creatine')->first();
        $vitamins = Category::where('slug', 'vitamins-health')->first();
        $fatburners = Category::where('slug', 'fat-burners')->first();

        $products = [
            [
                'name' => 'Gold Standard 100% Whey',
                'short_description' => 'The world\'s best-selling whey protein powder.',
                'full_description' => 'Optimum Nutrition\'s Gold Standard 100% Whey delivers 24g of whey protein per serving to support muscle growth and recovery.',
                'price' => 59.99,
                'old_price' => 69.99,
                'discount_percent' => 14.28,
                'brand_id' => $on?->id,
                'category_id' => $protein?->id,
                'form' => 'Powder',
                'servings' => 74,
                'rating' => 4.8,
                'reviews_count' => 1250,
                'is_vegan' => false,
                'in_stock' => true,
                'quantity' => 120,
                'status' => 'active',
            ],
            [
                'name' => 'ISO100 Hydrolyzed Whey',
                'short_description' => 'Fast-absorbing, ultra-pure 100% whey isolate.',
                'full_description' => 'Dymatize ISO100 is formulated using a multi-step purification process that preserves important muscle-building protein fractions while removing excess carbohydrates, fat, lactose, and cholesterol.',
                'price' => 64.99,
                'old_price' => 74.99,
                'discount_percent' => 13.33,
                'brand_id' => $dymatize?->id,
                'category_id' => $protein?->id,
                'form' => 'Powder',
                'servings' => 71,
                'rating' => 4.9,
                'reviews_count' => 840,
                'is_vegan' => false,
                'in_stock' => true,
                'quantity' => 85,
                'status' => 'active',
            ],
            [
                'name' => 'C4 Original Pre-Workout',
                'short_description' => 'Explosive energy and performance supplement.',
                'full_description' => 'Cellucor C4 Original is America\'s #1 selling pre-workout, featuring ingredients that help promote energy, endurance, and pumps.',
                'price' => 29.99,
                'old_price' => 34.99,
                'discount_percent' => 14.29,
                'brand_id' => $cellucor?->id,
                'category_id' => $preworkout?->id,
                'form' => 'Powder',
                'servings' => 30,
                'rating' => 4.6,
                'reviews_count' => 420,
                'is_vegan' => true,
                'in_stock' => true,
                'quantity' => 200,
                'status' => 'active',
            ],
            [
                'name' => 'NitroTech Whey Gold',
                'short_description' => 'Pure protein formula featuring whey peptides and isolate.',
                'full_description' => 'MuscleTech Nitro-Tech 100% Whey Gold features high-quality whey protein peptides and isolate to support rapid muscle recovery.',
                'price' => 54.99,
                'old_price' => 64.99,
                'discount_percent' => 15.38,
                'brand_id' => $muscletech?->id,
                'category_id' => $protein?->id,
                'form' => 'Powder',
                'servings' => 70,
                'rating' => 4.7,
                'reviews_count' => 650,
                'is_vegan' => false,
                'in_stock' => true,
                'quantity' => 90,
                'status' => 'active',
            ],
            [
                'name' => 'Impact Whey Isolate',
                'short_description' => 'Premium whey protein with over 90% protein content.',
                'full_description' => 'MyProtein Impact Whey Isolate is grade-A lab-tested protein powder, containing 23g of protein per serving with low fat and sugar content.',
                'price' => 49.99,
                'old_price' => 59.99,
                'discount_percent' => 16.67,
                'brand_id' => $myprotein?->id,
                'category_id' => $protein?->id,
                'form' => 'Powder',
                'servings' => 40,
                'rating' => 4.5,
                'reviews_count' => 310,
                'is_vegan' => false,
                'in_stock' => true,
                'quantity' => 150,
                'status' => 'active',
            ],
            [
                'name' => 'Platinum 100% Creatine',
                'short_description' => 'Ultra-pure micronized creatine powder.',
                'full_description' => 'MuscleTech Platinum 100% Creatine delivers micronized creatine directly to your muscles to drive performance, strength, and lean muscle mass.',
                'price' => 19.99,
                'old_price' => 24.99,
                'discount_percent' => 20.00,
                'brand_id' => $muscletech?->id,
                'category_id' => $creatine?->id,
                'form' => 'Powder',
                'servings' => 80,
                'rating' => 4.9,
                'reviews_count' => 950,
                'is_vegan' => true,
                'in_stock' => true,
                'quantity' => 300,
                'status' => 'active',
            ],
            [
                'name' => 'Daily Multivitamin Active',
                'short_description' => 'Essential vitamins and minerals for active lifestyles.',
                'full_description' => 'A comprehensive multivitamin supplement formulated with key micronutrients to boost immunity, energy, and overall health.',
                'price' => 14.99,
                'old_price' => 19.99,
                'discount_percent' => 25.00,
                'brand_id' => $myprotein?->id,
                'category_id' => $vitamins?->id,
                'form' => 'Tablet',
                'servings' => 60,
                'rating' => 4.6,
                'reviews_count' => 180,
                'is_vegan' => true,
                'in_stock' => true,
                'quantity' => 180,
                'status' => 'active',
            ],
            [
                'name' => 'Hydroxycut Hardcore Fat Burner',
                'short_description' => 'Advanced weight loss and thermogenic formula.',
                'full_description' => 'MuscleTech Hydroxycut Hardcore delivers intense energy and calorie-burning support to achieve your weight loss goals.',
                'price' => 39.99,
                'old_price' => 49.99,
                'discount_percent' => 20.00,
                'brand_id' => $muscletech?->id,
                'category_id' => $fatburners?->id,
                'form' => 'Capsule',
                'servings' => 100,
                'rating' => 4.4,
                'reviews_count' => 290,
                'is_vegan' => false,
                'in_stock' => false,
                'quantity' => 0,
                'status' => 'active',
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                $product
            );
        }
    }
}
