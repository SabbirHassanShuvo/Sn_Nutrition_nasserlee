<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PartnerProfile;
use App\Models\PayoutMethod;
use App\Models\PayoutRequest;
use Carbon\Carbon;

class AffiliateTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed or Update Sandip User (sandip@gmail.com)
        $sandip = User::where('email', 'sandip@gmail.com')->first();
        if (!$sandip) {
            $sandip = User::create([
                'name' => 'Sandip',
                'email' => 'sandip@gmail.com',
                'password' => bcrypt('password123'),
                'role' => 'health_professional',
            ]);
        }

        // Add $5,000.00 pending payout balance to Sandip
        PartnerProfile::updateOrCreate(
            ['user_id' => $sandip->id],
            [
                'current_tier' => 'gold',
                'lifetime_earnings' => 19420.55,
                'pending_payout' => 5000.00,
                'last_paid_amount' => 3890.00,
            ]
        );

        // Ensure Sandip has default payout methods
        if (!PayoutMethod::where('user_id', $sandip->id)->exists()) {
            PayoutMethod::create([
                'user_id' => $sandip->id,
                'type' => 'bank_account',
                'bank_name' => 'Chase Bank',
                'account_name' => 'Sandip',
                'account_number' => '4827192837',
                'routing_number' => '021000021',
                'fees_info' => 'Free',
                'delivery_time' => '1-3 business days',
                'is_default' => true,
            ]);

            PayoutMethod::create([
                'user_id' => $sandip->id,
                'type' => 'debit_card',
                'bank_name' => 'Visa Card',
                'account_name' => 'Sandip',
                'account_number' => '4111111111112917',
                'card_last_four' => '2917',
                'card_type' => 'Visa',
                'expiry_date' => '12/28',
                'fees_info' => '0.25% + $0.25',
                'delivery_time' => 'within 24 hours',
                'is_default' => false,
            ]);
        }

        // 2. Also setup User ID 1 if exists
        $firstUser = User::first();
        if ($firstUser && $firstUser->id !== $sandip->id) {
            PartnerProfile::updateOrCreate(
                ['user_id' => $firstUser->id],
                [
                    'current_tier' => 'gold',
                    'lifetime_earnings' => 19420.55,
                    'pending_payout' => 5000.00,
                    'last_paid_amount' => 3890.00,
                ]
            );
        }

        $this->command->info('Sandip pending payout balance updated to $5,000.00 successfully!');
    }
}
