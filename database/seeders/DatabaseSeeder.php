<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            SystemSeeder::class,
            PageSeeder::class,
            BannerSectionSeeder::class,
            QualityControlSectionSeeder::class,
            AboutSectionSeeder::class,
            HowItWorksSectionSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            FaqCategorySeeder::class,
            FaqSeeder::class,
            WebSettingSeeder::class,
            BlogSeeder::class,
            OnboardingQuestionSeeder::class,
            ContactSubmissionSeeder::class,
            SubscriberSeeder::class,
            OfferSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
