<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Optimum Nutrition',
                'specialty' => 'Whey Protein & Supplements',
                'rating' => 4.8,
                'status' => 'active',
            ],
            [
                'name' => 'MuscleTech',
                'specialty' => 'Performance Supplements',
                'rating' => 4.6,
                'status' => 'active',
            ],
            [
                'name' => 'Dymatize',
                'specialty' => 'Hydrolyzed Protein',
                'rating' => 4.7,
                'status' => 'active',
            ],
            [
                'name' => 'Cellucor',
                'specialty' => 'Pre-workout Energy',
                'rating' => 4.5,
                'status' => 'active',
            ],
            [
                'name' => 'MyProtein',
                'specialty' => 'Bulk Supplements & Snacks',
                'rating' => 4.4,
                'status' => 'active',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                $brand
            );
        }
    }
}
