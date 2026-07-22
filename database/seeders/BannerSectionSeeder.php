<?php

namespace Database\Seeders;

use App\Models\BannerSection;
use Illuminate\Database\Seeder;

class BannerSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing banners first to avoid duplicates
        BannerSection::truncate();

        $banners = [
            [
                'small_badge' => 'Trusted by Health Professionals',
                'title' => 'Better Health Starts Here',
                'title_highlight' => 'Health',
                'description' => 'Discover high-quality supplements designed to support your energy, immunity, and daily well-being — with fast, simple ordering.',
                'description_highlight' => 'energy, immunity, and daily well-being',
                'button_text' => 'Order Now',
                'button_link' => '/shop',
                'image' => 'assets/images/banner/hero-bg-1.png',
                'point_1' => 'Fast Delivery',
                'point_2' => 'Trusted Quality',
                'point_3' => 'Easy Ordering',
                'priority' => 1,
                'status' => BannerSection::STATUS['ACTIVE'],
            ],
            [
                'small_badge' => 'Recommended by Medical Experts',
                'title' => 'Where Better Health Begins',
                'title_highlight' => 'Better',
                'description' => 'Support your healthiest life with trusted supplements designed for daily wellness, immune support, and lasting vitality. Shop quickly and confidently.',
                'description_highlight' => 'wellness, immune support, and lasting vitality.',
                'button_text' => 'Order Now',
                'button_link' => '/shop',
                'image' => 'assets/images/banner/hero-bg-2.png',
                'point_1' => 'Fast Delivery',
                'point_2' => 'Trusted Quality',
                'point_3' => 'Easy Ordering',
                'priority' => 2,
                'status' => BannerSection::STATUS['ACTIVE'],
            ],
            [
                'small_badge' => 'Doctor Recommended Wellness',
                'title' => 'Healthy Living Starts Today',
                'title_highlight' => 'Living',
                'description' => 'Take the next step toward better health with carefully selected supplements made to support your body, boost your energy, and enhance your daily routine.',
                'description_highlight' => 'supplements made to support your body, boost your energy, and enhance ',
                'button_text' => 'Order Now',
                'button_link' => '/shop',
                'image' => 'assets/images/banner/hero-bg-3.png',
                'point_1' => 'Fast Delivery',
                'point_2' => 'Trusted Quality',
                'point_3' => 'Easy Ordering',
                'priority' => 3,
                'status' => BannerSection::STATUS['ACTIVE'],
            ],
            [
                'small_badge' => 'Clinically Trusted Supplements',
                'title' => 'Trusted Experts Proven Wellness',
                'title_highlight' => 'Experts',
                'description' => 'Experience premium wellness products designed to nourish your body, support your lifestyle, and help you thrive every day. Ordering is quick, easy, and reliable.',
                'description_highlight' => 'support your lifestyle, and help you thrive every day.',
                'button_text' => 'Order Now',
                'button_link' => '/shop',
                'image' => 'assets/images/banner/hero-bg-4.png',
                'point_1' => 'Fast Delivery',
                'point_2' => 'Trusted Quality',
                'point_3' => 'Easy Ordering',
                'priority' => 4,
                'status' => BannerSection::STATUS['ACTIVE'],
            ],
        ];

        foreach ($banners as $banner) {
            BannerSection::create($banner);
        }
    }
}
