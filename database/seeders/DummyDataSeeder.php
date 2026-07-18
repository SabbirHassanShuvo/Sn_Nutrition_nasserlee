<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create 3 Brands
        $brands = [];
        for ($i = 1; $i <= 3; $i++) {
            $name = "Brand " . $i;
            $brands[] = Brand::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'image' => null,
                    'specialty' => 'Supplements',
                    'rating' => 4.5,
                    'status' => 'active',
                ]
            );
        }

        // 2. Create 3 Categories
        $categories = [];
        $colors = ['#FF8000', '#53CD90', '#6FA1E4'];
        for ($i = 1; $i <= 3; $i++) {
            $name = "Category " . $i;
            $categories[] = Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'image' => null,
                    'color' => $colors[$i - 1],
                    'status' => 'active',
                ]
            );
        }

        // 3. Create 3 Products
        $products = [];
        for ($i = 1; $i <= 3; $i++) {
            $name = "Product " . $i;
            $products[] = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'short_description' => 'Short description for ' . $name,
                    'full_description' => 'Full detailed description for ' . $name,
                    'price' => rand(50, 150),
                    'old_price' => rand(160, 200),
                    'brand_id' => $brands[$i - 1]->id,
                    'category_id' => $categories[$i - 1]->id,
                    'servings' => 30,
                    'quantity' => 100,
                    'rating' => 4.5,
                    'reviews_count' => rand(10, 50),
                    'is_vegan' => (bool)rand(0, 1),
                    'in_stock' => true,
                    'is_popular' => true,
                    'status' => 'active',
                ]
            );
        }

        // 4. Create 3 Promo Codes
        $promoCodes = [];
        $types = ['global', 'category', 'product'];
        for ($i = 1; $i <= 3; $i++) {
            $promoCodes[] = PromoCode::updateOrCreate(
                ['code' => 'PROMO2026_' . $i],
                [
                    'type' => $types[$i - 1],
                    'category_id' => $types[$i - 1] === 'category' ? $categories[0]->id : null,
                    'product_id' => $types[$i - 1] === 'product' ? $products[0]->id : null,
                    'discount_percent' => rand(10, 30),
                    'usage_limit' => 100,
                    'per_user_limit' => 1,
                    'used_count' => 0,
                    'status' => 1,
                    'expiry_date' => now()->addMonths(3),
                ]
            );
        }

        // Create a test user if one doesn't exist
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]);
        }

        // 5. Create 3 Orders (with OrderItems)
        for ($i = 1; $i <= 3; $i++) {
            $product = $products[$i - 1];
            $qty = rand(1, 3);
            $subtotal = $product->price * $qty;
            $deliveryFee = 50;
            $total = $subtotal + $deliveryFee;

            $order = Order::updateOrCreate(
                ['order_number' => 'ORD-DUMMY-' . $i],
                [
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'discount' => 0,
                    'total' => $total,
                    'status' => 'pending',
                    'phone' => '1234567890',
                    'full_name' => 'John Doe ' . $i,
                    'email' => 'johndoe'.$i.'@example.com',
                    'city' => 'Metropolis',
                    'address' => '123 Super St',
                    'postal_code' => '12345',
                    'country' => 'USA',
                    'delivery_method' => 'standard',
                    'payment_method' => 'cod',
                ]
            );

            OrderItem::firstOrCreate([
                'order_id' => $order->id,
                'product_id' => $product->id,
            ], [
                'quantity' => $qty,
                'price' => $product->price,
            ]);
        }
    }
}
