<?php

namespace Database\Seeders;

use App\Models\HowItWorksSection;
use Illuminate\Database\Seeder;

class HowItWorksSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HowItWorksSection::truncate();

        HowItWorksSection::create([
            // Banner Section
            'banner_small_badge'         => 'Trusted by Health Professionals',
            'banner_title'               => 'Make Money From Your Nutrition Tips!',
            'banner_title_highlight_1'   => 'Money',
            'banner_title_highlight_2'   => 'Nutrition',
            'banner_description'         => 'The all-in-one partner portal for health professionals. Recommend practitioner-grade supplements, track every order in real time, and get paid on the 2nd of every month.',
            'banner_button_text'         => 'Start earning free →',
            'banner_button_link'         => '/register',
            'banner_point_1'             => 'Free forever',
            'banner_point_2'             => 'No clients required',
            'banner_point_3'             => '5-min setup',

            // Banner Right Side Mockups
            'banner_earnings_value'      => '$3,820.20',
            'banner_earnings_comparison' => 'vs $3,310 last month',
            'banner_earnings_change'     => '+16%',
            'banner_category1_name'      => 'Vitamins',
            'banner_category1_percent'   => '80%',
            'banner_category2_name'      => 'Protein',
            'banner_category2_percent'   => '62%',
            'banner_category3_name'      => 'Probiotics',
            'banner_category3_percent'   => '48%',

            // Stats Section
            'stat1_value' => '4,200+',
            'stat1_label' => 'Health pros',
            'stat2_value' => '$8.4M',
            'stat2_label' => 'Paid out in 2024',
            'stat3_value' => '98.6%',
            'stat3_label' => 'On-time payouts',
            'stat4_value' => '25%',
            'stat4_label' => 'Top commission',

            // Features Section
            'features_title'           => 'Built for the way you actually work.',
            'features_title_highlight' => 'actually work.',
            'features_description'     => 'From your first link to your hundredth payout — every tool a modern health professional needs to earn from referrals, in one clean dashboard.',
            
            'feature1_title'       => 'Smart referral links',
            'feature1_description' => 'Generate trackable, branded links for any product in seconds. Share via email, socials or your clinic site.',

            'feature2_title'       => 'Real-time analytics',
            'feature2_description' => 'Watch clicks, conversions, and earnings update live with detailed breakdowns by product and channel.',

            'feature3_title'       => 'Reliable monthly payouts',
            'feature3_description' => 'Get paid the 2nd of every month via Bank, PayPal, Stripe or Wise. $50 minimum, 14-day clearing.',

            'feature4_title'       => 'Tier rewards',
            'feature4_description' => 'Climb from Bronze to Platinum and unlock bonuses up to +5% extra commission per order.',

            'feature5_title'       => 'Practitioner-grade products',
            'feature5_description' => 'Promote only trusted, third-party tested supplements your clients can rely on.',

            'feature6_title'       => 'Order management',
            'feature6_description' => 'Track every customer order from your links status, amount, and commission details in one place.',

            // Steps Section
            'steps_title'             => 'From signup to first payout in days, not months.',
            'steps_title_highlight'   => 'not months.',
            'steps_description'       => 'Access all the essential tools for health professionals to effortlessly manage referrals and track earnings in a streamlined dashboard.',

            'step1_title'       => 'Sign up & verify',
            'step1_description' => 'Create your free professional account and complete a quick credentials check.',

            'step2_title'       => 'Pick your products',
            'step2_description' => 'Browse the catalog and generate branded links for the supplements you trust.',

            'step3_title'       => 'Share with clients',
            'step3_description' => 'Share your links in chats, newsletters, or social media; every order links back to you.',

            'step4_title'       => 'Get paid monthly',
            'step4_description' => 'Track every commission in real time and receive payouts via your preferred method.',

            // Tiers Section
            'tiers_title'             => 'The more you grow, the more you earn.',
            'tiers_title_highlight'   => 'the more you earn.',
            'tiers_description'       => 'Tiers refresh every month based on referred sales. Climb the ladder, unlock higher commissions and exclusive perks.',

            'tier1_name'        => 'Bronze',
            'tier1_commission'  => '5%',
            'tier1_sales'       => 'Commission • $0 sales',

            'tier2_name'        => 'Silver',
            'tier2_commission'  => '10%',
            'tier2_sales'       => 'Commission • $1k / mo sales',

            'tier3_name'        => 'Gold',
            'tier3_commission'  => '18%',
            'tier3_sales'       => 'Commission • $5k / mo sales',

            'tier4_name'        => 'Platinum',
            'tier4_commission'  => '25%',
            'tier4_sales'       => 'Commission • $15k / mo sales',
        ]);
    }
}
