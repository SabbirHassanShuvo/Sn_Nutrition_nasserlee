<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gym::create([
            'name' => 'FitZone Gym',
            'address' => '12 Rue Mohammed V, Casablanca',
            'latitude' => 33.5731104,
            'longitude' => -7.5898434,
            'rating' => 4.8,
            'opening_hours' => '06:00 - 22:00',
            'facilities' => ['Cardio', 'Weights', 'Classes'],
            'is_active' => true,
        ]);

        Gym::create([
            'name' => 'PowerHouse Fitness',
            'address' => '34 Boulevard Anfa, Casablanca',
            'latitude' => 33.5851104,
            'longitude' => -7.6018434,
            'rating' => 4.5,
            'opening_hours' => '07:00 - 23:00',
            'facilities' => ['CrossFit', 'Sauna', 'Pool'],
            'is_active' => true,
        ]);

        Gym::create([
            'name' => 'Elite Sports Club',
            'address' => '7 Rue Ibnou Sina, Casablanca',
            'latitude' => 33.5921104,
            'longitude' => -7.6128434,
            'rating' => 4.3,
            'opening_hours' => '06:30 - 21:00',
            'facilities' => ['Yoga', 'Boxing', 'Weights'],
            'is_active' => true,
        ]);

        Gym::create([
            'name' => 'Urban Athlete',
            'address' => '88 Avenue Hassan II, Casablanca',
            'latitude' => 33.5981104,
            'longitude' => -7.6208434,
            'rating' => 4.6,
            'opening_hours' => '06:00 - 22:30',
            'facilities' => ['HIIT', 'Cycling', 'Nutrition'],
            'is_active' => true,
        ]);
    }
}
