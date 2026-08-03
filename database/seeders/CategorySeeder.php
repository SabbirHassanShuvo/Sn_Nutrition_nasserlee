<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Whey Protein',
                'color' => '#FF8000',
                'status' => 'active',
            ],
            [
                'name' => 'Pre-Workout',
                'color' => '#53CD90',
                'status' => 'active',
            ],
            [
                'name' => 'Creatine',
                'color' => '#6FA1E4',
                'status' => 'active',
            ],
            [
                'name' => 'Vitamins & Health',
                'color' => '#A86FE4',
                'status' => 'active',
            ],
            [
                'name' => 'Fat Burners',
                'color' => '#E46F88',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category
            );
        }
    }
}
