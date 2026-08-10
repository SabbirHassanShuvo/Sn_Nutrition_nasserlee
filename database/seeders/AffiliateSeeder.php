<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\AffiliateLink;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PartnerProfile;
use App\Models\PayoutMethod;
use App\Models\PayoutRequest;
use App\Models\AffiliateTierSetting;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AffiliateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Affiliate Tier Settings if missing
        $tiers = [
            ['tier_key' => 'bronze', 'tier_name' => 'Bronze', 'min_earnings' => 0.00, 'bonus_percent' => 0.00, 'badge_color' => '#cd7f32'],
            ['tier_key' => 'silver', 'tier_name' => 'Silver', 'min_earnings' => 1500.00, 'bonus_percent' => 2.00, 'badge_color' => '#c0c0c0'],
            ['tier_key' => 'gold', 'tier_name' => 'Gold', 'min_earnings' => 3000.00, 'bonus_percent' => 5.00, 'badge_color' => '#ffd700'],
            ['tier_key' => 'platinum', 'tier_name' => 'Platinum', 'min_earnings' => 6000.00, 'bonus_percent' => 8.00, 'badge_color' => '#e5e4e2'],
        ];

        foreach ($tiers as $tier) {
            AffiliateTierSetting::updateOrCreate(
                ['tier_key' => $tier['tier_key']],
                $tier
            );
        }

        // 2. Ensure Categories Exist
        $categoryData = [
            ['name' => 'Hair, Skin & Nails', 'slug' => 'hair-skin-nails', 'color' => '#A86FE4', 'image' => 'uploads/categories/hair-skin-nails-6a5251d355bda.png'],
            ['name' => 'Digestion', 'slug' => 'digestion', 'color' => '#53CD90', 'image' => 'uploads/categories/digestion-6a52520c76f0f.png'],
            ['name' => 'Whey Protein', 'slug' => 'whey-protein', 'color' => '#FF8000', 'image' => null],
            ['name' => 'Vitamins & Health', 'slug' => 'vitamins-health', 'color' => '#6FA1E4', 'image' => null],
            ['name' => 'Pre-Workout', 'slug' => 'pre-workout', 'color' => '#E46F88', 'image' => null],
        ];

        $categories = [];
        foreach ($categoryData as $cat) {
            $categories[] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'color' => $cat['color'],
                    'image' => $cat['image'],
                    'status' => 'active',
                ]
            );
        }

        // 3. Ensure Products Exist
        $productData = [
            [
                'name' => 'Curran Wynn Ashwagandha Root',
                'slug' => 'curran-wynn-ashwagandha',
                'price' => 120.00,
                'commission_percent' => 10.00,
                'category_id' => $categories[0]->id,
                'main_image' => 'uploads/products/1421395-swanson-ashwagandha-supplement-ashwagandha-root-aerial-parts-supplement-promoting-stress-relief-ener-6a5254992ec17.webp',
                'status' => 'active',
            ],
            [
                'name' => 'Probiotic Digestion Support',
                'slug' => 'probiotic-digestion-support',
                'price' => 65.00,
                'commission_percent' => 12.00,
                'category_id' => $categories[1]->id,
                'main_image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Gold Standard 100% Whey Protein',
                'slug' => 'gold-standard-100-whey-protein',
                'price' => 85.00,
                'commission_percent' => 8.00,
                'category_id' => $categories[2]->id,
                'main_image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'Daily Multivitamin & Mineral Complex',
                'slug' => 'daily-multivitamin-mineral-complex',
                'price' => 45.00,
                'commission_percent' => 15.00,
                'category_id' => $categories[3]->id,
                'main_image' => null,
                'status' => 'active',
            ],
            [
                'name' => 'C4 Explosive Pre-Workout Energy Powder',
                'slug' => 'c4-explosive-pre-workout-energy-powder',
                'price' => 55.00,
                'commission_percent' => 10.00,
                'category_id' => $categories[4]->id,
                'main_image' => null,
                'status' => 'active',
            ],
        ];

        $products = [];
        foreach ($productData as $p) {
            $products[] = Product::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }

        // 4. Seed Users and Affiliate Data
        $users = User::all();
        if ($users->isEmpty()) {
            $users = collect([
                User::create([
                    'name' => 'Md Sabbir Hassan',
                    'email' => 'sabbirhassan@example.com',
                    'password' => bcrypt('12345678'),
                    'role' => 'health_professional',
                ])
            ]);
        }

        $now = Carbon::now();

        foreach ($users as $user) {
            // A. Seed Partner Profile
            PartnerProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'current_tier' => 'gold',
                    'lifetime_earnings' => 19420.55,
                    'pending_payout' => 5000.00,
                    'last_paid_amount' => 3890.00,
                    'onboarding_step' => 4,
                ]
            );

            // B. Seed Payout Methods
            PayoutMethod::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'bank_account'],
                [
                    'bank_name' => 'Chase Bank',
                    'account_name' => $user->name ?: 'Partner User',
                    'account_number' => '4827192837',
                    'routing_number' => '021000021',
                    'fees_info' => 'Free',
                    'delivery_time' => '1-3 business days',
                    'is_default' => true,
                ]
            );

            PayoutMethod::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'debit_card'],
                [
                    'bank_name' => 'Visa Card',
                    'account_name' => $user->name ?: 'Partner User',
                    'account_number' => '4111111111112917',
                    'card_last_four' => '2917',
                    'card_type' => 'Visa',
                    'expiry_date' => '12/28',
                    'fees_info' => '0.25% + $0.25',
                    'delivery_time' => 'within 24 hours',
                    'is_default' => false,
                ]
            );

            // C. Seed Payout Requests / History
            $payoutSampleData = [
                ['payout_number' => 'PO-9822-' . $user->id, 'type' => 'bank_account', 'amount' => 4520.90, 'status' => 'pending', 'created_at' => $now->copy()->subDays(3)],
                ['payout_number' => 'PO-9821-' . $user->id, 'type' => 'bank_account', 'amount' => 3890.42, 'status' => 'paid', 'created_at' => $now->copy()->subMonths(1), 'paid_at' => $now->copy()->subMonths(1)->addDays(1)],
                ['payout_number' => 'PO-9820-' . $user->id, 'type' => 'bank_account', 'amount' => 3120.18, 'status' => 'paid', 'created_at' => $now->copy()->subMonths(2), 'paid_at' => $now->copy()->subMonths(2)->addDays(1)],
                ['payout_number' => 'PO-9819-' . $user->id, 'type' => 'debit_card', 'amount' => 2640.55, 'status' => 'paid', 'created_at' => $now->copy()->subMonths(3), 'paid_at' => $now->copy()->subMonths(3)->addDays(1)],
            ];

            foreach ($payoutSampleData as $po) {
                PayoutRequest::updateOrCreate(
                    ['payout_number' => $po['payout_number']],
                    [
                        'user_id' => $user->id,
                        'type' => $po['type'],
                        'amount' => $po['amount'],
                        'bank_name' => $po['type'] === 'bank_account' ? 'Chase Bank' : 'Visa Debit',
                        'account_name' => $user->name ?: 'Partner User',
                        'account_number' => $po['type'] === 'bank_account' ? '4827192837' : '4111111111112917',
                        'routing_number' => $po['type'] === 'bank_account' ? '021000021' : null,
                        'card_last_four' => $po['type'] === 'debit_card' ? '2917' : null,
                        'card_type' => $po['type'] === 'debit_card' ? 'Visa' : null,
                        'status' => $po['status'],
                        'created_at' => $po['created_at'],
                        'paid_at' => isset($po['paid_at']) ? $po['paid_at'] : null,
                    ]
                );
            }

            // D. Seed Affiliate Links & Orders
            foreach ($products as $index => $product) {
                $link = AffiliateLink::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'tracking_code' => Str::random(10),
                        'clicks_count' => rand(150, 450),
                        'conversions_count' => rand(15, 45),
                        'status' => 'active',
                    ]
                );

                // Create orders spanning across past months
                $monthsBack = [0, 0, 1, 1, 2, 3, 4, 5];
                foreach ($monthsBack as $mIndex => $m) {
                    $orderDate = $now->copy()->subMonths($m)->subDays(rand(1, 20));
                    $orderTotal = rand(150, 500) + rand(0, 99) / 100;
                    $commission = round($orderTotal * ($product->commission_percent / 100), 2);

                    $orderNumber = 'AFF-ORD-' . $user->id . '-' . $link->id . '-' . $mIndex . '-' . rand(100, 999);

                    $order = Order::updateOrCreate(
                        ['order_number' => $orderNumber],
                        [
                            'user_id' => $user->id,
                            'affiliate_link_id' => $link->id,
                            'subtotal' => $orderTotal,
                            'total' => $orderTotal,
                            'commission_amount' => $commission,
                            'status' => 'delivered',
                            'full_name' => 'Customer ' . rand(100, 999),
                            'email' => 'customer' . rand(100, 999) . '@example.com',
                            'phone' => '1234567890',
                            'created_at' => $orderDate,
                            'updated_at' => $orderDate,
                        ]
                    );

                    // Create Order Item for category breakdown
                    OrderItem::updateOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                        ],
                        [
                            'quantity' => rand(1, 3),
                            'price' => $product->price,
                            'created_at' => $orderDate,
                            'updated_at' => $orderDate,
                        ]
                    );
                }
            }
        }
    }
}
