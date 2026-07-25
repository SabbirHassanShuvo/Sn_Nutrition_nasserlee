<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\FaqCategory;

class FaqCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Orders & Shipping',    'priority' => 1],
            ['name' => 'Product & Ingredients','priority' => 2],
            ['name' => 'Shipping Policy',      'priority' => 3],
            ['name' => 'Returns & Refunds',    'priority' => 4],
        ];

        foreach ($categories as $cat) {
            FaqCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name'     => $cat['name'],
                    'slug'     => Str::slug($cat['name']),
                    'priority' => $cat['priority'],
                    'status'   => FaqCategory::STATUS['ACTIVE'],
                ]
            );
        }
    }
}
