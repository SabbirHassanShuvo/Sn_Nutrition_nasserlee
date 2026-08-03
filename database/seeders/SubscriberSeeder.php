<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Subscriber::create([
                'email'   => $faker->unique()->safeEmail,
                'is_read' => $faker->boolean(40), // 40% chance of being read (60% unread)
            ]);
        }
    }
}
